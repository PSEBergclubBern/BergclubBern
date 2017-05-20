<?php

/**
 * Adds a new menu entry for "Hintergrundbilder" under "Design" in admin.
 */
function bcb_theme_background_menu()
{
    add_theme_page('Hintergrundbilder', 'Hintergrundbilder', 'bcb_theme_images', 'bcb_theme_background', 'bcb_theme_background');
}

add_action('admin_menu', 'bcb_theme_background_menu');

/**
 * Checks if the given file has at least a resolution of 1920x1080 pixel
 * @param string $file The file path to the image
 * @return bool true if the image has at least 1920x1080 pixel, false otherwise
 */
function bcb_theme_background_check_size($file)
{
    $size = getimagesize($file);
    return $size[0] >= 1920 && $size[1] >= 1080;
}

/**
 * Resize a given file to max. 1920 pixel with or 1080 pixel height depending on which dimension is bigger.
 * Will return the unmodified image if the dimensions are smaller or equal than 1920 (width) or 1080 (height).
 * @param string $file The file path to the image
 * @return resource A gd image resource
 */
function bcb_theme_background_resize($file)
{
    $size = getimagesize($file);
    $width_orig = $size[0];
    $height_orig = $size[1];
    $image = imagecreatefromjpeg($file);
    if ($width_orig > 1920 || $height_orig > 1080) {
        $width_new = 1920;
        $ratio = $width_new / $width_orig;
        $height_new = round($height_orig * $ratio);

        if ($height_new < 1080) {
            $height_new = 1080;
            $ratio = $height_new / $height_orig;
            $width_new = round($width_orig * $ratio);
        }

        $image_resized = imagecreatetruecolor($width_new, $height_new);
        imagecopyresized($image_resized, $image, 0, 0, 0, 0, $width_new, $height_new, $width_orig, $height_orig);
        $image = $image_resized;
    }

    return $image;
}

/**
 * Resizes the given file (see bcb_theme_background_resize), generates a random file name, saves the image under this
 * filename and updates the WP Option (bcb_background_images).
 * @param string $file the temporary file path of the image.
 */
function bcb_theme_background_save($file)
{
    $image = bcb_theme_background_resize($file);
    $key = md5(time() . uniqid());
    $filename = '/img/carousel/' . $key . '.jpg';
    imagejpeg($image, __DIR__ . $filename, 60);
    $images = get_option('bcb_background_images');
    if (empty($images)) {
        $images = [];
    }

    $images[$key] = ['filename' => $filename, 'horizontal' => 'center', 'vertical' => 'center', 'active' => false];

    update_option('bcb_background_images', $images);
}

/**
 * Handles POST requests for the "Hintergrundbilder" page in WP Admin (Design > Hintergrundbilder)
 */
function bcb_theme_background_action()
{
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if ($_POST['action'] == 'upload') {
            $tmp_name = $_FILES['background-image']['tmp_name'];
            $type = $_FILES['background-image']['type'];
            if (empty($tmp_name)) {
                bcb_add_notice('danger', 'Bitte wählen Sie ein Bild von Ihrer Festplatte aus.', true, true);
            } elseif ($type != 'image/jpeg') {
                bcb_add_notice('danger', 'Das Bild muss im Format JPEG sein (Dateiendung: .jpg, .jpeg).', true, true);
            } elseif (!bcb_theme_background_check_size($tmp_name)) {
                bcb_add_notice('danger', 'Das Bild muss mindestens 1920 x 1080 Pixel gross sein.', true, true);
            } else {
                bcb_theme_background_save($tmp_name);
                bcb_add_notice('success', 'Das Bild wurde erfolgreich hochgeladen. Sie müssen es noch aktivieren.', true, true);
            }
        } else {
            $images = get_option('bcb_background_images');
            if ($_POST['action'] == 'save') {
                foreach ($_POST['background_images'] as $key => $arr) {
                    $images[$key] = array_merge($images[$key], $arr);
                }
            } elseif ($_POST['action'] == 'delete') {
                $key = $_POST['key'];
                $file = __DIR__ . $images[$key]['filename'];

                if (file_exists($file)) {
                    unlink($file);
                }
                unset($images[$key]);
            }

            update_option('bcb_background_images', $images);
        }
    }
}

/**
 * Displays the "Hintergrundbilder" page in WP Admin (Design > Hintergrundbilder)
 */
function bcb_theme_background()
{
    bcb_theme_background_action();
    echo "<h1>Hintergrundbilder</h1>";
    bcb_show_notice();
    echo "<h2>Neues Hintergrundbild hochladen:</h2>";
    echo "<form id='upload-form' method='post' enctype='multipart/form-data'>";
    echo "<input type='hidden' name='action' id='upload-action' value='upload'/>";
    echo "<p>Neues Bild hochladen <input id='background-image' name='background-image' type='file' accept='image/jpeg'/> <button type='button' class='button upload-button' id='upload-button'>Hochladen</button>";
    echo "<br/><i>Hinweis: Das Bild muss im JPEG Format und mindestens 1920 x 1080 Pixel gross sein.</i></p>";
    echo "</form>";

    echo "<h2>Vorhandene Hintergrundbilder:</h2>";

    $images = get_option('bcb_background_images');
    if (empty($images)) {
        echo "<p>Noch keine Hintergrundbilder vorhanden.</p>";
    } else {
        echo "<form id='images-form' method = 'post'>";
        echo "<input type='hidden' name='action' id='images-action' value='save'/>";
        echo "<input type='hidden' name='key' id='images-key' value=''/>";
        echo "<table cellpadding='10'>";
        echo "<tr>";
        echo "<td><strong>Bild</strong></td>";
        echo "<td><strong>Horizontale Anordnung</strong></td>";
        echo "<td><strong>Vertikale Anordnung</strong></td>";
        echo "<td><strong>Aktiv</strong></td>";
        echo "<td>&nbsp;</td>";
        echo "</tr>";
        foreach ($images as $key => $image) {
            echo "<tr>";
            echo "<td><img src='" . get_template_directory_uri() . $image['filename'] . "' style='max-width: 250px'/></td>";
            echo "<td>";
            echo "<input type='radio' name='background_images[" . $key . "][horizontal]' value='left'";
            if ($image['horizontal'] == 'left') {
                echo " checked";
            }
            echo "/> Links ";
            echo "<input type='radio' name='background_images[" . $key . "][horizontal]' value='center'";
            if ($image['horizontal'] == 'center') {
                echo " checked";
            }
            echo "/> Mitte ";
            echo "<input type='radio' name='background_images[" . $key . "][horizontal]' value='right'";
            if ($image['horizontal'] == 'right') {
                echo " checked";
            }
            echo "/> Rechts";
            echo "</td>";
            echo "<td>";
            echo "<input type='radio' name='background_images[" . $key . "][vertical]' value='top'";
            if ($image['vertical'] == 'top') {
                echo " checked";
            }
            echo "/> Oben ";
            echo "<input type='radio' name='background_images[" . $key . "][vertical]' value='center'";
            if ($image['vertical'] == 'center') {
                echo " checked";
            }
            echo "/> Mitte ";
            echo "<input type='radio' name='background_images[" . $key . "][vertical]' value='bottom'";
            if ($image['vertical'] == 'bottom') {
                echo " checked";
            }
            echo "/> Unten";
            echo "</td>";
            echo "<td><select name='background_images[" . $key . "][active]'><option value='0'>Nein</option><option value='1'";
            if ($image['active']) {
                echo " selected";
            }
            echo ">Ja</option></select></td>";
            echo "<td>";
            echo "<button type='button' class='button button-primary save-button'>Speichern</button> ";
            echo "<button type='button' class='button button-secondary delete-button' data-key='" . $key . "'>Löschen</button>";
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "</form>";
    }


    echo "<script type='text/javascript' src='" . get_template_directory_uri() . "/js/theme-background-image.js'></script>";
}