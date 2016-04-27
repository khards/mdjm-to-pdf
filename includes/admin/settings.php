<?php

/**
 * Registers the MDJM to PDF options sections
 * *
 * @since       1.0
 * @param 		$sections array the existing plugin sections
 * @return      array
*/
function mdjm_pdf_register_license_section( $sections ) {
	$sections['mdjm-to-pdf'] = __( 'PDF Settings', 'mdjm-to-pdf' );

	return $sections;
} // mdjm_pdf_register_license_section
add_filter( 'mdjm_settings_sections_extensions', 'mdjm_pdf_register_license_section', 10, 1 );

/**
 * Registers the MDJM to PDF setting options in Extensions
 * *
 * @since       1.0
 * @param 		$settings array the existing plugin settings
 * @return      array
*/
function mdjm_pdf_settings( $settings )	{
			
	$pdf_settings = array(
		'mdjm-to-pdf'	=> array(
			array(
				'id' => 'mdjm_to_pdf_header',
				'name' => '<strong>' . __( 'Options', 'mdjm-to-pdf' ) . '</strong>',
				'desc' => '',
				'type' => 'header'
			),
			array(
				'id'          => 'pdf_page_size',
				'name'        => __( 'Page Size', 'mdjm-to-pdf' ),
				'desc'        =>'',
				'type'		=> 'select',
				'options'     => array(
					'A1'		=> __( 'A1', 'mdjm-to-pdf' ),
					'A2'		=> __( 'A2', 'mdjm-to-pdf' ),
					'A3'		=> __( 'A3', 'mdjm-to-pdf' ),
					'A4'		=> __( 'A4', 'mdjm-to-pdf' ),
					'A5'		=> __( 'A5', 'mdjm-to-pdf' ),
					'B1'		=> __( 'B1', 'mdjm-to-pdf' ),
					'B2'		=> __( 'B2', 'mdjm-to-pdf' ),
					'B3'		=> __( 'B3', 'mdjm-to-pdf' ),
					'B4'		=> __( 'B4', 'mdjm-to-pdf' ),
					'B5'		=> __( 'B5', 'mdjm-to-pdf' ),
					'Letter'	=> __( 'Letter', 'mdjm-to-pdf' ),
					'Legal'	 => __( 'Legal', 'mdjm-to-pdf' )
				),
				'std'           => 'A4'
			),
			array(
				'id'          => 'pdf_page_orientation',
				'name'        => __( 'Page Orientation', 'mdjm-to-pdf' ),
				'desc'        => '',
				'type'        => 'select',
				'options'     => array(
					'P'		=> __( 'Portrait', 'mdjm-to-pdf' ),
					'L'		=> __( 'Landscape', 'mdjm-to-pdf' )
				),
				'std'         => 'P'
			),
			array(
				'id'          => 'pdf_watermark',
				'name'        => __( 'Watermark', 'mdjm-to-pdf' ),
				'desc'        => __( 'A watermark can be added to your PDF documents. Leave blank for none', 'mdjm-to-pdf' ),
				'type'        => 'text',
				'size'        => 'regular'
			),
			array(
				'id' => 'mdjm_to_pdf_templates_header',
				'name' => '<strong>' . __( 'Template Overrides', 'mdjm-to-pdf' ) . '</strong>',
				'desc' => '',
				'type' => 'header'
			),
			array(
				'id'          => 'pdf_enquiry_template',
				'name'        => __( 'Quote Template Attachment', 'mdjm-to-pdf' ),
				'desc'        => __( 'This is the template that will be attached to quotes via <code>email</code> to clients', 'mdjm-to-pdf' ),
				'type'        => 'select',
				'options'     => mdjm_list_templates( 'email_template' ),
				'std'         => mdjm_get_option( 'enquiry' )
			),
			array(
				'id'          => 'pdf_enquiry_text',
				'name'        => __( 'Enquiry Text', 'mdjm-to-pdf' ),
				'desc'        => __( 'Override the default template used when sending quotes via email to clients. Leave this text empty if you do <strong>not</strong> want to override', 'mdjm-to-pdf' ),
				'type'        => 'rich_editor'
			),
			array(
				'id'          => 'pdf_contract_template',
				'name'        => __( 'Contract Template Attachment', 'mdjm-to-pdf' ),
				'desc'        => __( 'This is the template that will be attached to contract emails', 'mdjm-to-pdf' ),
				'type'        => 'select',
				'options'     => mdjm_list_templates( 'email_template' ),
				'std'         => mdjm_get_option( 'contract' )
			),
			array(
				'id'          => 'pdf_contract_text',
				'name'        => __( 'Contract Text', 'mdjm-to-pdf' ),
				'desc'        => __( 'Override the default template used when sending contract notification emails to clients. Leave this text empty if you do <strong>not</strong> want to override', 'mdjm-to-pdf' ),
				'type'        => 'rich_editor'
			),
			array(
				'id'          => 'pdf_booking_conf_template',
				'name'        => __( 'Booking Confirmation Attachment', 'mdjm-to-pdf' ),
				'desc'        => __( 'This is the template that will be attached to booking confirmation emails', 'mdjm-to-pdf' ),
				'type'        => 'select',
				'options'     => mdjm_list_templates( 'email_template' ),
				'std'         => mdjm_get_option( 'booking_conf_client' )
			),
			array(
				'id'          => 'pdf_booking_conf_text',
				'name'        => __( 'Booking Confirmation Text', 'mdjm-to-pdf' ),
				'desc'        => __( 'Override the default template used when sending booking confirmation via email to clients. Leave this text empty if you do <strong>not</strong> want to override', 'mdjm-to-pdf' ),
				'type'        => 'rich_editor'
			)
		)
	);
	
	return array_merge( $settings, $pdf_settings );
} // mdjm_pdf_settings
add_filter( 'mdjm_settings_extensions', 'mdjm_pdf_settings');