<?php

/*
* Title                   : Pinpoint Booking System WordPress Plugin
* Version                 : 2.1.2
* File                    : views/settings/views-backend-settings-general.php
* File Version            : 1.0.3
* Created / Last Modified : 11 October 2015
* Author                  : Dot on Paper
* Copyright               : © 2012 Dot on Paper
* Website                 : http://www.dotonpaper.net
* Description             : Back end general settings views class.
*/

    if (!class_exists('DOPBSPViewsBackEndSettingsGeneral')){
        class DOPBSPViewsBackEndSettingsGeneral extends DOPBSPViewsBackEndSettings{
            /*
             * Constructor
             */
            function __construct(){
            }
            
            /*
             * Returns general settings template.
             * 
             * @param args (array): function arguments
             * 
             * @return general settings HTML
             */
            function template($args = array()){
                global $DOPBSP;
                
                $id = 0;
                $settings_general = $DOPBSP->classes->backend_settings->values($id,  
                                                                              'general');
?>
                <div class="dopbsp-inputs-header dopbsp-hide">
                    <h3><?php echo $DOPBSP->text('SETTINGS_CALENDAR_GENERAL_SETTINGS'); ?></h3>
                    <a href="javascript:DOPBSPBackEnd.toggleInputs('general-settings')" id="DOPBSP-inputs-button-calendar-general-settings" class="dopbsp-button"></a>
                </div>
                <div id="DOPBSP-inputs-general-settings" class="dopbsp-inputs-wrapper">
<?php 
                /*
                 * PINPOINT.WORLD refferal ID.
                 */
                $this->displayTextInput(array('id' => 'refferal_id',
                                              'label' => $DOPBSP->text('SETTINGS_GENERAL_REFFERAL_ID'),
                                              'value' => $settings_general->refferal_id,
                                              'settings_id' => $id,
                                              'settings_type' => 'general',
                                              'help' => $DOPBSP->text('SETTINGS_GENERAL_REFFERAL_ID_HELP')));
                /*
                 * Display refferal text in front-end calendar.
                 */
                $this->displaySwitchInput(array('id' => 'refferal_display',
                                                'label' => $DOPBSP->text('SETTINGS_GENERAL_REFFERAL_DISPLAY'),
                                                'value' => $settings_general->refferal_display,
                                                'settings_id' => $id,
                                                'settings_type' => 'general',
                                                'help' => $DOPBSP->text('SETTINGS_GENERAL_REFFERAL_DISPLAY_HELP')));
?>
                </div>
<?php
            }
        }
    }