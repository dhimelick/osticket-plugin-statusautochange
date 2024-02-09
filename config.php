<?php

require_once INCLUDE_DIR . 'class.plugin.php';
require_once INCLUDE_DIR . 'class.list.php';

class StatusAutoChangePluginConfig extends PluginConfig {

    function pre_save(&$config, &$errors) {
        if ($config['toStatus'] == '') {
            $errors['err'] = 'You must select a status.';
            return FALSE;
        }
        return TRUE;
    }

    function getOptions() {
        $fromStatuses = array('' => 'Any');
        foreach (TicketStatusList::getStatuses(array('states' => array('open', 'closed'))) as $s) {
            $fromStatuses[$s->getId()] = $s->getName();
        }
		
		$fromDefault = '';

        $toStatuses = array();
        foreach (TicketStatusList::getStatuses(array('states' => array('open', 'closed'))) as $s) {
            $toStatuses[$s->getId()] = $s->getName();
        }
		
		$toDefault = '';
		if (count($toStatuses) > 0) {
			$toDefault = array_key_first($toStatuses);
		}

        return array(
            'header' => new SectionBreakField(array(
                'label' => 'Ticket Status Auto-changer',
                'hint' => 'When a customer replies...'
            )),
            'fromStatus' => new ChoiceField(array(
                'default' => $fromDefault,
                'label' => 'Change status if original status is',
                'choices' => $fromStatuses
            )),
            'toStatus' => new ChoiceField(array(
                'default' => $toDefault,
                'label' => 'Change status to',
                'choices' => $toStatuses
            ))
        );
    }
}
