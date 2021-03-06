<?php

/**
 * Invoice Payment Meta
 *
 * Display the invoice data meta box.
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * GetPaid_Meta_Box_Invoice_Payment_Meta Class.
 */
class GetPaid_Meta_Box_Invoice_Payment_Meta {

    /**
	 * Output the metabox.
	 *
	 * @param WP_Post $post
	 */
    public static function output( $post ) {

        // Prepare the invoice.
        $invoice = new WPInv_Invoice( $post );

        ?>

        <style>
            #wpinv-payment-meta label {
                margin-bottom: 3px;
                font-weight: 600;
            }
        </style>
                <div class="bsui" style="margin-top: 1.5rem">

                    <div class="wpinv-payment-meta">

                    <?php

                        // Invoice key.
                        echo aui()->input(
                            array(
                                'type'        => 'text',
                                'id'          => 'wpinv_key',
                                'name'        => 'wpinv_key',
                                'label'       => sprintf(
                                    __( '%s Key:', 'invoicing' ),
                                    ucfirst( $invoice->get_invoice_quote_type() )
                                ),
                                'label_type'  => 'vertical',
                                'class'       => 'form-control-sm',
                                'value'       => $invoice->get_key( 'edit' ),
                                'extra_attributes' => array(
                                    'onclick'  => 'this.select();',
                                    'readonly' => 'true',
                                ),
                            )
                        );

                        // View URL.
                        echo aui()->input(
                            array(
                                'type'        => 'text',
                                'id'          => 'wpinv_view_url',
                                'name'        => 'wpinv_view_url',
                                'label'       => sprintf(
                                    __( '%s URL:', 'invoicing' ),
                                    ucfirst( $invoice->get_invoice_quote_type() )
                                ),
                                'label_type'  => 'vertical',
                                'class'       => 'form-control-sm',
                                'value'       => $invoice->get_view_url(),
                                'extra_attributes' => array(
                                    'onclick'  => 'this.select();',
                                    'readonly' => 'true',
                                ),
                            )
                        );

                        // If the invoice is paid...
                        if ( $invoice->is_paid() || $invoice->is_refunded() ) {

                            // Payment date.
                            echo aui()->input(
                                array(
                                    'type'        => 'datepicker',
                                    'id'          => 'wpinv_date_completed',
                                    'name'        => 'date_completed',
                                    'label'       => __( 'Payment Date:', 'invoicing' ),
                                    'label_type'  => 'vertical',
                                    'placeholder' => 'YYYY-MM-DD 00:00',
                                    'class'       => 'form-control-sm',
                                    'value'       => $invoice->get_date_completed( 'edit' ),
                                    'extra_attributes' => array(
                                        'data-enable-time' => 'true',
                                        'data-time_24hr'   => 'true',
                                        'data-allow-input' => 'true',
                                    ),
                                )
                            );

                            // Gateway.
                            echo aui()->input(
                                array(
                                    'type'        => 'text',
                                    'id'          => 'wpinv_gateway',
                                    'name'        => 'wpinv_gateway',
                                    'label'       => __( 'Gateway:', 'invoicing' ),
                                    'label_type'  => 'vertical',
                                    'class'       => 'form-control-sm',
                                    'value'       => wpinv_get_gateway_admin_label( $invoice->get_gateway( 'edit' ) ),
                                    'extra_attributes' => array(
                                        'onclick'  => 'this.select();',
                                        'readonly' => 'true',
                                    ),
                                )
                            );

                            // Transaction ID.
                            echo aui()->input(
                                array(
                                    'type'        => 'text',
                                    'id'          => 'wpinv_transaction_id',
                                    'name'        => 'wpinv_transaction_id',
                                    'label'       => __( 'Transaction ID:', 'invoicing' ),
                                    'label_type'  => 'vertical',
                                    'class'       => 'form-control-sm',
                                    'value'       => $invoice->get_transaction_id( 'edit' ),
                                    'help_text'   => apply_filters( 'wpinv_invoice_transaction_link_' . $invoice->get_gateway( 'edit' ), '', $invoice->get_transaction_id(), $invoice ),
                                    'extra_attributes' => array(
                                        'onclick'  => 'this.select();',
                                        'readonly' => 'true',
                                    ),
                                )
                            );

                            // Currency.
                            echo aui()->input(
                                array(
                                    'type'        => 'text',
                                    'id'          => 'wpinv_currency',
                                    'name'        => 'wpinv_currency',
                                    'label'       => __( 'Currency:', 'invoicing' ),
                                    'label_type'  => 'vertical',
                                    'class'       => 'form-control-sm',
                                    'value'       => $invoice->get_currency( 'edit' ),
                                    'extra_attributes' => array(
                                        'onclick'  => 'this.select();',
                                        'readonly' => 'true',
                                    ),
                                )
                            );

                        } else {

                            if ( 'wpi_invoice' == $invoice->get_post_type() ) {

                                // Payment URL.
                                echo aui()->input(
                                    array(
                                        'type'        => 'text',
                                        'id'          => 'wpinv_payment_url',
                                        'name'        => 'wpinv_payment_url',
                                        'label'       => __( 'Payment URL:', 'invoicing' ),
                                        'label_type'  => 'vertical',
                                        'class'       => 'form-control-sm',
                                        'value'       => $invoice->get_checkout_payment_url(),
                                        'extra_attributes' => array(
                                            'onclick'  => 'this.select();',
                                            'readonly' => 'true',
                                        ),
                                    )
                                );

                                // Set gateway.
                                echo aui()->select(
                                    array(
                                        'id'               => 'wpinv_gateway',
                                        'name'             => 'wpinv_gateway',
                                        'label'            => __( 'Gateway:', 'invoicing' ),
                                        'label_type'       => 'vertical',
                                        'placeholder'      => __( 'Select Gateway', 'invoicing' ),
                                        'value'            => $invoice->get_gateway( 'edit' ),
                                        'select2'          => true,
                                        'data-allow-clear' => 'false',
                                        'options'          => wp_list_pluck( wpinv_get_enabled_payment_gateways( true ), 'admin_label' ),
                                    )
                                );

                            }




                        }

                    ?>
                    </div>
                </div>

        <?php
    }
}
