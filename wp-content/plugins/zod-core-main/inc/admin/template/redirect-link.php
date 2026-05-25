<div class="form-repeater">

    <div data-repeater-list="zod_core_settings[redirect_link]">
            <?php
                $settings = get_option('zod_core_settings');
                if( isset( $settings['redirect_link'] ) ){
                    foreach( $settings['redirect_link'] as $index => $item ){
                        ?>
                        <div data-repeater-item>
                            <table class="form-table">
                                <tr>
                                    <th>
                                        <label for="zod_core_settings[redirect_link][<?php esc_attr_e( $index ); ?>][redirect_from]"><?php _e( 'Redirect URL From', 'zod-core' ) ?></label> 
                                    </th>
                                    <td>
                                        <input type="text" class="w-100" name="zod_core_settings[redirect_link][<?php esc_attr_e( $index ); ?>][redirect_from]" value="<?php esc_attr_e( $item['redirect_from'] ); ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label for="zod_core_settings[redirect_link][<?php esc_attr_e( $index ); ?>][redirect_to]"><?php _e( 'Redirect URL To', 'zod-core' ) ?></label> 
                                    </th>
                                    <td>
                                        <input type="text" class="w-100" name="zod_core_settings[redirect_link][<?php esc_attr_e( $index ); ?>][redirect_to]" value="<?php esc_attr_e( $item['redirect_to'] ); ?>">
                                    </td>
                                    <td>
                                        <input data-repeater-delete="" type="button" value="Delete">
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <?php
                    }
                }
                if( empty( $settings['redirect_link'] ) ){
                    ?>
                    <div data-repeater-item>
                        <table class="form-table">
                            <tr>
                                <th>
                                    <label for="redirect_from"><?php _e( 'Redirect URL From', 'zod-core' ) ?></label> 
                                </th>
                                <td>
                                    <input type="text" class="w-100" name="redirect_from">
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="redirect_to"><?php _e( 'Redirect URL To', 'zod-core' ) ?></label> 
                                </th>
                                <td>
                                    <input type="text" class="w-100" name="redirect_to">
                                </td>
                                <td>
                                    <input data-repeater-delete="" type="button" value="Delete">
                                </td>
                            </tr>
                        </table>
                    </div>
                    <?php
                }
            ?>
        </table>
    </div>
    <a href="#" class="add-item" data-repeater-create type="button">Add</a>
</div>
<?php
