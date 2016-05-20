<?php
	defined( 'ABSPATH' ) or die( "Direct access to this page is disabled!!!" );
/**
 * Class Name: MDJM_PDF_Processor
 * Description: Process MDJM to PDF actions
 * 
 * 
 * Author: My DJ Planner <contact@mydjplanner.co.uk>
 * Author URI: http://www.mydjplanner.co.uk
 */
if( !class_exists( 'MDJM_PDF_Processor' ) ) :
	class MDJM_PDF_Processor	{
		/**
		 * Class constructor
		 *
		 *
		 *
		 */
		function __construct()	{
			add_action( 'template_redirect', array( &$this, 'pdf_output' ) ); // PDF needs to be processed before any HTML output
									
			add_filter( 'mdjm_quote_email_args', array( &$this, 'quote_email' ) );
			
			add_filter( 'mdjm_contract_email_args', array( &$this, 'contract_email' ) );
			
			add_filter( 'mdjm_booking_conf_email_args', array( &$this, 'booking_conf_email' ) );
			
			add_filter( 'mdjm_send_comm_email_attachments', array( &$this, 'pdf_attach' ), 10, 2 ); // Attach the PDF file to emails
			
			add_filter( 'mdjm_enquiry_subject', array( &$this, 'set_quote_subject' ) );
			add_filter( 'mdjm_email_content_enquiry', array( &$this, 'set_quote_content' ) );
			add_filter( 'mdjm_enquiry_attachments', array( &$this, 'add_quote_attachment' ), 10, 2 );
			
			add_filter( 'mdjm_contract_subject', array( &$this, 'set_contract_subject' ) );
			add_filter( 'mdjm_email_content_contract', array( &$this, 'set_contract_content' ) );
			add_filter( 'mdjm_contract_attachments', array( &$this, 'add_contract_attachment' ), 10, 2 );
			
			add_filter( 'mdjm_booking_conf_subject', array( &$this, 'set_booking_conf_subject' ) );
			add_filter( 'mdjm_email_content_booking_conf', array( &$this, 'set_booking_conf_content' ) );
			add_filter( 'mdjm_booking_conf_attachments', array( &$this, 'add_booking_conf_attachment' ), 10, 2 );
			
			add_action( 'mdjm_add_comms_fields_before_content', array( &$this, 'comms_page_add_pdf_attachment_input' ) ); // Add attachment option to comms page
		} // __construct
		
		/**
		 * Initialise mPDF ready for use
		 *
		 * @param	arr		$args		Optional: required parameters for mPDF
		 *
		 *								
		 *
		 */
		function init_mpdf( $args='' )	{
			global $mdjm_mpdf;

			$mode = !empty( $args['mode'] ) ? $args['mode'] : '';
			$format = !empty( $args['orientation'] ) ? $args['orientation'] : mdjm_get_option( 'pdf_page_size' );
			$font_size = !empty( $args['font_size'] ) ? $args['font_size'] : 0;
			$font = !empty( $args['font'] ) ? $args['font'] : '';
			$margin_left = !empty( $args['margin_left'] ) ? $args['margin_left'] : '15';
			$margin_right = !empty( $args['margin_right'] ) ? $args['margin_right'] : '15';
			$margin_top = !empty( $args['margin_top'] ) ? $args['margin_top'] : '16';
			$margin_bottom = !empty( $args['margin_bottom'] ) ? $args['margin_bottom'] : '16';
			$margin_header = !empty( $args['margin_header'] ) ? $args['margin_header'] : '9';
			$margin_footer = !empty( $args['margin_footer'] ) ? $args['margin_footer'] : '9';
			$orientation = !empty( $args['orientation'] ) ? $args['orientation'] : mdjm_get_option( 'pdf_page_orientation' );
			
			include( MDJM_PDF_PLUGIN_DIR . '/includes/mpdf/mpdf.php' );
			
			$mdjm_mpdf = new mPDF( 
							$mode,
							$format,
							$font_size,
							$font,
							$margin_left,
							$margin_right,
							$margin_top,
							$margin_bottom,
							$margin_header,
							$margin_footer,
							$orientation );
			
			$mdjm_mpdf->debug = false; // Debugging is controlled by MDJM Debug settings
			$mdjm_mpdf->allow_output_buffering = true;
		} // init_mpdf
		
		/**
		 * Set the PDF File Metadata
		 *
		 *
		 *
		 */
		function set_pdf_meta( $title='', $author='', $creator='', $subject='' )	{
			global $mdjm_mpdf;
			
			$title = !empty( $title ) ? $title : '';
			
			if( !empty( $title ) )
				$mdjm_mpdf->SetTitle( $title );
				
			$author = !empty( $author ) ? $author : MDJM_COMPANY;
			
			$mdjm_mpdf->SetAuthor( $author );
				
			$creator = !empty( $creator ) ? $creator : MDJM_COMPANY;
			
			$mdjm_mpdf->SetCreator( $creator );
				
			$subject = !empty( $subject ) ? $subject : '';
			
			if( !empty( $subject ) )
				$mdjm_mpdf->SetSubject( $subject );
				
			if( mdjm_get_option( 'pdf_watermark' ) )	{
				$mdjm_mpdf->SetWatermarkText( mdjm_get_option( 'pdf_watermark' ), 0.2 );
				$mdjm_mpdf->showWatermarkText = true;
			}
		} // set_pdf_meta
		
		/**
		 * Output the content to PDF and deliver as required
		 * All vars should be sent via the URL query string
		 * 
		 * @param		pdf_output	int		Required: The ID of the post we will convert to PDF
		 *				output_to	str		Optional: How do we want the file outputted? 
		 *										I->Browser (default), D->Download, F->Save file local to server, S->string
		 *
		 */
		function pdf_output()	{
			global $mdjm_mpdf, $mdjm;
			
			if( !isset( $_GET['pdf_output'] ) )
				return;
			
			$poss_output = array( 'I', 'D', 'F', 'S' );
			
			$output = isset( $_GET['output_to'] ) && in_array( $_GET['output_to'], $poss_output ) ? $_GET['output_to'] : 'I';
			
			$data = $this->pdf_content();
			
			// Data is a post
			if( is_numeric( $data ) )	{	
				$template = get_post( $data );
				
				$content = $template->post_content;
				$content = apply_filters( 'the_content', $content );
				$content = str_replace( ']]>', ']]&gt;', $content );
			}
			// Data is a string
			else
				$content = $data;
			
			$this->init_mpdf();
			//$stylesheet = file_get_contents(get_template_directory_uri() . '/style.css');
			//$this->mdjm_pdf->WriteHTML( $stylesheet,1 );
			$this->set_pdf_meta();
			$mdjm_mpdf->WriteHTML( $content, 2 );
			
			// Set the filename if required
			switch( $output )	{
				case 'I':
					$file = MDJM_COMPANY . '.pdf';
				break;
				case 'D':
					$file = MDJM_COMPANY . '.pdf';
				break;
				case 'F':
				$upload_dir = wp_upload_dir();
					$file = $upload_dir['path'] . '/' . str_replace( ' ', '_', MDJM_COMPANY ) . '.pdf';
				break;
				case 'S':
					$file = '';
				break;	
			}
			
			$mdjm_mpdf->Output( $file, $output );
			exit;
		} // pdf_output
		
		/**
		 * Retrieve the content for the PDF file
		 *
		 *
		 *
		 * @return		int|arr		The content for the PDF file
		 */
		function pdf_content()	{
			global $post, $mdjm;
			
			if( !empty( $post ) )	{
				// Quote
				if( $post->ID == MDJM_QUOTES_PAGE )	{
					$quoteID = $mdjm->mdjm_events->retrieve_quote( $_GET['event_id'] );
					
					return $quoteID;
				}
				// Contract
				elseif( $post->ID == MDJM_CONTRACT_PAGE )	{
					$status = array( 'mdjm-approved', 'mdjm-completed' );
					$signed = get_post_meta( $_GET['event_id'], '_mdjm_signed_contract', true );
					$unsigned = get_post_meta( $_GET['event_id'], '_mdjm_event_contract', true );
					
					if( in_array( get_post_status( $_GET['event_id'] ), $status ) && !empty( $signed ) )
						return $signed;
						
					else	{ // Unsigned contracts need filtering
						$template = get_post( $unsigned );
						
						// Let's add space for a signatory
						$signing = '<hr />';
						$signing .= '<h3>' . strtoupper( __( 'CONTRACT ACCEPTANCE', 'mdjm-to-pdf' ) ) . '</h3>';
						$signing .= '<p>' . __( 'By signing this contract I hereby confirm that the person named within the contract is me and that all associated details are correct.', 'mdjm-to-pdf' ) . '</p>';
						$signing .= '<p>' .__( 'My signature confirms that I have read, understood and accept the terms of this contract.', 'mdjm-to-pdf' ) . '</p>';
						$signing .= '<table style="border: none; border-collapse: collapse; width: 75%; float: left;">';
						$signing .= '<tr>';
						$signing .= '<th style="text-align: left; width: 25%; height: 50px; vertical-align:bottom;">Full Name: </th>';
						$signing .= '<td style="border-bottom: 1px solid #000; text-align: left; vertical-align:bottom;">{CLIENT_FULLNAME}</td>';
						$signing .= '</tr>';
						$signing .= '<tr>';
						$signing .= '<th style="text-align: left; height: 50px; vertical-align:bottom;">Signature: </th>';
						$signing .= '<td style="border-bottom: 1px solid #000; text-align: left; vertical-align:bottom;">&nbsp;</td>';
						$signing .= '</tr>';
						$signing .= '<tr>';
						$signing .= '<th style="text-align: left; height: 50px; vertical-align:bottom;">Date (' . strtoupper( MDJM_SHORTDATE_FORMAT  ) . '): </th>';
						$signing .= '<td style="text-align: left; vertical-align:bottom;">_________/________/________________</td>';
						$signing .= '</tr>';
						$signing .= '</table>';
						
						$content = $template->post_content . $signing;
						$content = apply_filters( 'the_content', $content );
						$content = str_replace( ']]>', ']]&gt;', $content );
						
						$eventinfo = $mdjm->mdjm_events->event_detail( $_GET['event_id'] );
						
						$content = $mdjm->filter_content( $eventinfo['client']->ID, $_GET['event_id'], $content );
						
						return $content;
					}
				}
			}
		} // pdf_content
		
		/**
		 * Sets the subject for quote emails.
		 *
		 * @since	0.4
		 * @param	str		$subject	The existing subject to filter.
		 * @return	str		The filtered subject.
		 */
		public function set_quote_subject( $subject )	{
			
			if ( ! mdjm_get_option( 'pdf_enquiry_text' ) )	{
				return $subject;
			} else	{
				return get_the_title( mdjm_get_option( 'pdf_enquiry_template' ) );
			}
			
		} // set_quote_subject
		
		/**
		 * Sets the content for quote emails.
		 *
		 * @since	0.4
		 * @param	str		$content	The existing content to filter.
		 * @return	str		The filtered content.
		 */
		public function set_quote_content( $content )	{
			
			$new_content = mdjm_get_option( 'pdf_enquiry_text' );
			
			if ( empty ( $new_content ) )	{
				return $content;
			} else	{
				return $new_content;
			}
			
		} // set_quote_content
		
		/**
		 * Generate the PDF for enquiries, filter content tags and attach to email.
		 *
		 * @since	0.4
		 * @param	arr		$attachments	The existing attachments.
		 * @param	obj		$mdjm_event		The event post object
		 * @return	arr		The filtered attachments.
		 */
		public function add_quote_attachment( $attachments, $mdjm_event )	{

			global $mdjm_mpdf;
			
			if ( ! mdjm_get_option( 'pdf_enquiry_text' ) )	{
				return $attachments;
			}
			
			$template = get_post( mdjm_get_option( 'pdf_enquiry_template' ) );
			
			if ( ! $template )	{
				return $attachments;
			}
				
			$attach = $template->post_content;
			$attach = apply_filters( 'the_content', $attach );
			$attach = str_replace( ']]>', ']]&gt;', $attach );						
			$attach = mdjm_do_content_tags( $attach, $mdjm_event->ID, $mdjm_event->client );
			
			// Generate the PDF file
			$this->init_mpdf();
			$this->set_pdf_meta(
				get_the_title( mdjm_get_option( 'pdf_enquiry_template' ) ),
				'',
				'',
				get_the_title( mdjm_get_option( 'pdf_enquiry_template' ) )
			);
			
			$mdjm_mpdf->WriteHTML( $attach, 2 );
			
			// Save the file
			$upload_dir = wp_upload_dir();
			
			$file = $upload_dir['path'] . '/' . str_replace( ' ', '_', mdjm_get_option( 'company_name' ) ) . '_' . mdjm_get_event_contract_id( $mdjm_event->ID ) . '-' . date( 'Y-m-d H:i:s' ) . '.pdf';
			
			$mdjm_mpdf->Output( $file, 'F' );
			
			if( ! file_exists( $file ) )	{
				MDJM()->debug->log_it( 'PDF file was not created successfully or cannot be found in ' . __METHOD__, true );
				
				return $attachments;	
			}
			
			// Now add the attachment to the email_args array
			$attachments[] = $file;
			
			return $attachments;
			
		} // add_quote_attachment
		
		/**
		 * Sets the subject for awaiting contract emails.
		 *
		 * @since	0.4
		 * @param	str		$subject	The existing subject to filter.
		 * @return	str		The filtered subject.
		 */
		public function set_contract_subject( $subject )	{
			
			if ( ! mdjm_get_option( 'pdf_contract_text' ) )	{
				return $subject;
			} else	{
				return get_the_title( mdjm_get_option( 'pdf_contract_template' ) );
			}
			
		} // set_contract_subject
		
		/**
		 * Sets the content for awaiting contract emails.
		 *
		 * @since	0.4
		 * @param	str		$content	The existing content to filter.
		 * @return	str		The filtered content.
		 */
		public function set_contract_content( $content )	{
			
			$new_content = mdjm_get_option( 'pdf_contract_text' );
			
			if ( empty ( $new_content ) )	{
				return $content;
			} else	{
				return $new_content;
			}
			
		} // set_contract_content
		
		/**
		 * Generate the PDF for awaiting contract emails, filter content tags and attach to email.
		 *
		 * @since	0.4
		 * @param	arr		$attachments	The existing attachments.
		 * @param	obj		$mdjm_event		The event post object
		 * @return	arr		The filtered attachments.
		 */
		public function add_contract_attachment( $attachments, $mdjm_event )	{

			global $mdjm_mpdf;
			
			if ( ! mdjm_get_option( 'pdf_contract_text' ) )	{
				return $attachments;
			}
			
			$template = get_post( mdjm_get_option( 'pdf_contract_template' ) );
			
			if ( ! $template )	{
				return $attachments;
			}
				
			$attach = $template->post_content;
			$attach = apply_filters( 'the_content', $attach );
			$attach = str_replace( ']]>', ']]&gt;', $attach );						
			$attach = mdjm_do_content_tags( $attach, $mdjm_event->ID, $mdjm_event->client );
			
			// Generate the PDF file
			$this->init_mpdf();
			$this->set_pdf_meta(
				get_the_title( mdjm_get_option( 'pdf_contract_template' ) ),
				'',
				'',
				get_the_title( mdjm_get_option( 'pdf_contract_template' ) )
			);
			
			$mdjm_mpdf->WriteHTML( $attach, 2 );
			
			// Save the file
			$upload_dir = wp_upload_dir();
			
			$file = $upload_dir['path'] . '/' . str_replace( ' ', '_', mdjm_get_option( 'company_name' ) ) . '_' . mdjm_get_event_contract_id( $mdjm_event->ID ) . '-' . date( 'Y-m-d H:i:s' ) . '.pdf';
			
			$mdjm_mpdf->Output( $file, 'F' );
			
			if( ! file_exists( $file ) )	{
				MDJM()->debug->log_it( 'PDF file was not created successfully or cannot be found in ' . __METHOD__, true );
				
				return $attachments;	
			}
			
			// Now add the attachment to the email_args array
			$attachments[] = $file;
			
			return $attachments;
			
		} // add_contract_attachment
		
		/**
		 * Sets the subject for booking confirmation emails.
		 *
		 * @since	0.4
		 * @param	str		$subject	The existing subject to filter.
		 * @return	str		The filtered subject.
		 */
		public function set_booking_conf_subject( $subject )	{
			
			if ( ! mdjm_get_option( 'pdf_booking_conf_text' ) )	{
				return $subject;
			} else	{
				return get_the_title( mdjm_get_option( 'pdf_booking_conf_template' ) );
			}
			
		} // set_booking_conf_subject
		
		/**
		 * Sets the content for booking confirmation emails.
		 *
		 * @since	0.4
		 * @param	str		$content	The existing content to filter.
		 * @return	str		The filtered content.
		 */
		public function set_booking_conf_content( $content )	{
			
			$new_content = mdjm_get_option( 'pdf_booking_conf_text' );
			
			if ( empty ( $new_content ) )	{
				return $content;
			} else	{
				return $new_content;
			}
			
		} // set_booking_conf_content
		
		/**
		 * Generate the PDF for booking confirmation emails, filter content tags and attach to email.
		 *
		 * @since	0.4
		 * @param	arr		$attachments	The existing attachments.
		 * @param	obj		$mdjm_event		The event post object
		 * @return	arr		The filtered attachments.
		 */
		public function add_booking_conf_attachment( $attachments, $mdjm_event )	{
			
			global $mdjm_mpdf;
			
			if ( ! mdjm_get_option( 'pdf_booking_conf_text' ) )	{
				return $attachments;
			}
			
			$template = get_post( mdjm_get_option( 'pdf_booking_conf_template' ) );
			
			if ( ! $template )	{
				return $attachments;
			}
				
			$attach = $template->post_content;
			$attach = apply_filters( 'the_content', $attach );
			$attach = str_replace( ']]>', ']]&gt;', $attach );						
			$attach = mdjm_do_content_tags( $attach, $mdjm_event->ID, $mdjm_event->client );
			
			// Generate the PDF file
			$this->init_mpdf();
			$this->set_pdf_meta(
				get_the_title( mdjm_get_option( 'pdf_booking_conf_template' ) ),
				'',
				'',
				get_the_title( mdjm_get_option( 'pdf_booking_conf_template' ) )
			);
			
			$mdjm_mpdf->WriteHTML( $attach, 2 );
			
			// Save the file
			$upload_dir = wp_upload_dir();
			
			$file = $upload_dir['path'] . '/' . str_replace( ' ', '_', mdjm_get_option( 'company_name' ) ) . '_' . mdjm_get_event_contract_id( $mdjm_event->ID ) . '-' . date( 'Y-m-d H:i:s' ) . '.pdf';
			
			$mdjm_mpdf->Output( $file, 'F' );
			
			if( ! file_exists( $file ) )	{
				MDJM()->debug->log_it( 'PDF file was not created successfully or cannot be found in ' . __METHOD__, true );
				
				return $attachments;	
			}
			
			// Now add the attachment to the email_args array
			$attachments[] = $file;
			
			return $attachments;
			
		} // add_booking_conf_attachment
				
		/**
		 * Adds the selected PDF file from the comms page as an attachment to the email
		 *
		 * @param		arr		$files		Files currently attached to email
		 * @param		arr		$data		Wuper global $_POST data
		 *
		 * @return		arr		$files		Filtered files attached to email
		 */
		function pdf_attach( $files, $data )	{
			global $mdjm_mpdf;
			
			// Only process if we need to
			if( empty( $data['pdf_attach'] ) )	{
				return $files;
			}
			
			// We can only filter the content if we have an event
			if( empty( $data['mdjm_email_event'] ) )	{
				
				MDJM()->debug->log_it( 'Cannot create PDF file. No event specified in ' . __METHOD__, true );
				
				return $files;

			}
			
			$mdjm_event = new MDJM_Event( $data['mdjm_email_event'] );
			
			// Prepare the content
			$template = get_post( $data['pdf_attach'] );
			
			// If the template does not exist return
			if( ! $template )	{
				MDJM()->debug->log_it( 'Cannot create PDF file. The specified template (' . $data['pdf_attach'] . ') does not exist in ' . __METHOD__, true );
				return $files;
			}
				
			$content  = $template->post_content;
			$content  = apply_filters( 'the_content', $content );
			$content  = str_replace( ']]>', ']]&gt;', $content );
			
			// Run the content through the Content Filter
			$content  = mdjm_do_content_tags( $content, $mdjm_event->ID, $mdjm_event->client );
			
			// Generate the PDF file
			$this->init_mpdf();
			$this->set_pdf_meta();
			$mdjm_mpdf->WriteHTML( $content, 2 );
			
			// Create the file and save
			$upload_dir = wp_upload_dir();
			$file       = $upload_dir['path'] . '/' . str_replace( ' ', '_', mdjm_get_option( 'company_name' ) ) . '_' . mdjm_get_event_contract_id( $_POST['mdjm_email_event'] ) . '-' . date( 'Y-m-d H:i:s' ) . '.pdf';
			$mdjm_mpdf->Output( $file, 'F' );
			
			if( !file_exists( $file ) )	{
				MDJM()->debug->log_it( 'PDF file was not created successfully or cannot be found in ' . __METHOD__, true );
				
				return $files;	
			}
			
			$files[] = $file;
			
			return $files;
		} // pdf_attach
		
		/**
		 * Add the attach template as PDF option to the comms page
		 *
		 *
		 * @params		obj		$email_query		Post object array of email templates
		 *						$contract_query		Post object array of contract templates
		 *
		 */
		function comms_page_add_pdf_attachment_input()	{
			?>
            <tr>
                <th scope="row"><label for="pdf_attach"><?php _e( 'Attach a Template', 'mdjm-to-pdf' ); ?>:</label></th>
                <td>
                	<select name="pdf_attach" id="pdf_attach">
                    <option value="0"<?php if( !isset( $_GET['attach'], $_POST['pdf_attach'] ) || $_GET['attach'] == '0' ) echo ' selected="selected"'; ?>>
						<?php _e( 'No Attachment', 'mdjm-to-pdf' ); ?>
                    </option>
                    
					<?php echo mdjm_comms_template_options(); ?>
            </select>
            </td>
            </tr>
            <?php
		} // comms_page_add_pdf_attachment_input
			
	} // class MDJM_PDF_Processor
endif;
	new MDJM_PDF_Processor();