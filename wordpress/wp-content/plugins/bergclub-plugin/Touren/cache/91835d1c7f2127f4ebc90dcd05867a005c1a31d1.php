<table>
    <tbody>
        <tr>
            <th align="left">Datum von</th>
            <td>
                <input type="text"
                       class="bcb-date-picker-field"
                       id="<?php echo e(\BergclubPlugin\Touren\MetaBoxes\Common::DATE_FROM_IDENTIFIER); ?>"
                       name="<?php echo e(\BergclubPlugin\Touren\MetaBoxes\Common::DATE_FROM_IDENTIFIER); ?>"
                       value="<?php echo e($values[ \BergclubPlugin\Touren\MetaBoxes\Common::DATE_FROM_IDENTIFIER ]); ?>">
            </td>
        </tr>
        <tr>
            <th align="left">Datum bis</th>
            <td>
                <input type="text"
                       class="bcb-date-picker-field"
                       id="<?php echo e(\BergclubPlugin\Touren\MetaBoxes\Common::DATE_TO_IDENTIFIER); ?>"
                       name="<?php echo e(\BergclubPlugin\Touren\MetaBoxes\Common::DATE_TO_IDENTIFIER); ?>"
                       value="<?php echo e($values[ \BergclubPlugin\Touren\MetaBoxes\Common::DATE_TO_IDENTIFIER ]); ?>">
            </td>
        </tr>
        <tr>
            <th align="left">Leiter</th>
            <td>
                <select name="<?php echo e(\BergclubPlugin\Touren\MetaBoxes\Common::LEADER); ?>">
                    <?php $__currentLoopData = $leiter; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($user->ID == $values[ \BergclubPlugin\Touren\MetaBoxes\Common::LEADER]): ?>
                            <option selected="selected" value="<?php echo e($user->ID); ?>"><?php echo e($user->last_name); ?> <?php echo e($user->first_name); ?></option>
                        <?php else: ?>
                            <option value="<?php echo e($user->ID); ?>"><?php echo e($user->last_name); ?> <?php echo e($user->first_name); ?></option>
                        <?php endif; ?>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </td>
        </tr>
    </tbody>
</table>