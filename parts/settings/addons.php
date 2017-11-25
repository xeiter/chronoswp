<?php

namespace ChronosWP;

/*
 Title: Addons list
 Setting: chronoswp-addons
 */

$chronoswp = new Core();

$addons = $chronoswp->getAllAddons();

?>

<table class="table widefat">

    <thead>

        <tr>
            <th class="row-title narrow">

                <fieldset>
                    <legend class="screen-reader-text"><span>Fieldset Example</span></legend>
                    <label for="users_can_register">
                        <input name="" type="checkbox" id="users_can_register" value="1" />
                    </label>
                </fieldset>

            </th>

            <th class="row-title">Addon</th>
            <th class="row-title">Available elements</th>
            <th class="row-title">Dependenices</th>
            <th class="row-title">Actions</th>
        </tr>

    </thead>

    <tbody>

        <?php foreach ( $addons as $addon ) : ?>

            <?php

            $name = $addon->getName();
            $dependencies = $addon->getDependencies();
            $version = $addon->getCurrentVersion();
            $description = $addon->getDescription();
            $elements = $addon->getElements();
            $addon->setActive(true);

            ?>

            <tr>

                <!-- Enabled ? -->
                <td class="checkbox">
                    <input name="" type="checkbox" id="users_can_register" value="1" <?= $addon->isActive() ? 'checked="checked"' : '' ?> />
                </td>
                <!-- END: Enabled ? -->

                <!-- Addon name -->
                <td class="row-title">
                    <?= $name ?> <span class="badge"><?= $version ?></span>
                    <p><small><?= $description ?></small></p>
                </td>
                <!-- END: Addon name -->

				<!-- Elements -->
				<td>
					<?php foreach ( $elements as $reference => $label ) : ?>
						<small><?= $label; ?> <span class="secondary-details">(<?= $reference; ?>)</span></small><br/>
					<?php endforeach; ?>
				</td>
				<!-- END: Elements -->

                <!-- Addon dependencies -->
                <td>

                    <?php if ( ! empty( $dependencies ) ) : ?>

                        <?php foreach ( $dependencies as $dependency ) : ?>
                            <small><?= $dependency->getName(); ?></small><br/>
                        <?php endforeach; ?>

                    <?php else : ?>

                        <small>No dependencies</small>

                    <?php endif; ?>


                </td>
                <!-- END: Addon dependencies -->

            </tr>

        <?php endforeach; ?>

    </tbody>

    <tfoot>

        <tr>
            <th class="row-title">

                <fieldset>
                    <legend class="screen-reader-text"><span>Fieldset Example</span></legend>
                    <label for="users_can_register">
                        <input name="" type="checkbox" id="users_can_register" value="1" />
                    </label>
                </fieldset>

            </th>

            <th class="row-title">Addon</th>
            <th class="row-title">Available elements</th>
            <th class="row-title">Dependenices</th>
            <th class="row-title">Actions</th>
        </tr>

    </tfoot>

</table>