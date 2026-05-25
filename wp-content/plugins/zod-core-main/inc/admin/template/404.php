<?php
$selected_page = isset( $settings['404_page' ] ) ? $settings['404_page'] : ''; 
$args = array(
    'post_type'    => 'page',
    'orderby'      => 'menu_order'
);
$pages = get_pages( $args );

$select_option = array();

$select_option[''] = __('Select a Page');

foreach( $pages as $page ){
    $select_option[$page->ID] = __( $page->post_title, 'zod-core' );
}

$wrapper = sprintf(
    '
    <table class="form-table">
        <tr>
            <th>
                <label for="zod_core_settings[zod_404]">%1$s</label> 
                <p>%2$s</p>
            </th>
            <td class="va-top">
                %3$s
            </td>
        </tr>
    </table>
    ',
    __( 'Select 404 Page', 'zod-core' ),
    __( 'This is description', 'zod-core' ),
    zod_core_select( array(
        'name'      => 'zod_core_settings[404_page]',
        'id'        => 'zod-404-select',
        'value'     => esc_attr( $selected_page ),
        'options'   => $select_option
    ), false )
);

echo apply_filters( 'zod_404_select', $wrapper );