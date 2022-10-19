<?php

require_once INCLUDE_DIR . 'class.plugin.php';
require_once INCLUDE_DIR . 'class.list.php';

class StatusAutoChangePluginConfig extends PluginConfig {

    function pre_save(&$config, &$errors) {
        if ($config['clientReplyStatus'] == '') {
            $errors['err'] = 'You must select a status.';
            return FALSE;
        }
        return TRUE;
    }

    function getOptions() {
        $statuses = array();
        foreach (TicketStatusList::getStatuses(array('states' => array('open', 'closed'))) as $s) {
            $statuses[$s->getId()] = $s->getName();
        }
		
		$default = '';
		if (count($statuses) > 0) {
			$default = array_key_first($statuses);
		}

        return array(
            'clientReplyStatus' => new ChoiceField(array(
                'default' => $default,
                'label' => 'When a client replies, status becomes',
                'choices' => $statuses
            ))
        );
    }
}
