<?php
require_once(INCLUDE_DIR . 'class.signal.php');
require_once(INCLUDE_DIR . 'class.plugin.php');
require_once(INCLUDE_DIR . 'class.ticket.php');
require_once(INCLUDE_DIR . 'class.osticket.php');
require_once(INCLUDE_DIR . 'class.config.php');
require_once(INCLUDE_DIR . 'class.format.php');
require_once('config.php');

class StatusAutoChangePlugin extends Plugin {

    var $config_class = 'StatusAutoChangePluginConfig';

    /**
     * The entrypoint of the plugin, keep short, always runs.
     */
    function bootstrap() {
        // listen for osTicket to tell us it's updated an existing ticket
        Signal::connect('threadentry.created', array($this, 'onTicketUpdated'));
    }
    /**
     * What to do with an Updated Ticket?
     * 
     * @global OsticketConfig $cfg
     * @param ThreadEntry $entry
     * @return type
     */
    function onTicketUpdated(ThreadEntry $entry) {
        global $cfg;
        if (!$cfg instanceof OsticketConfig) {
            error_log("Slack plugin called too early.");
            return;
        }
        if (!$entry instanceof MessageThreadEntry) {
            // this was a reply or a system entry, not a message from a user
            return;
        }
        // need to fetch the ticket from the ThreadEntry
        $ticket = $this->getTicket($entry);
        if (!$ticket instanceof Ticket) {
            // Admin created tickets won't work here.
            return;
        }
        // check to make sure this entry isn't the first (ie: a New ticket)
        $first_entry = $ticket->getMessages()[0];
        if ($entry->getId() == $first_entry->getId()) {
            return;
        }
        // change status based on config
        $new_status = TicketStatus::lookup($this->getConfig()->get('clientReplyStatus'));
        if (!is_null($new_status) && $ticket->getStatusId() != $new_status->getId()) {
            $ticket->setStatus($new_status);
        }
    }

    /**
     * Fetches a ticket from a ThreadEntry.
     *
     * From https://github.com/clonemeagain/osticket-slack.
     *
     * @param ThreadEntry $entry            
     * @return Ticket
     */
    function getTicket(ThreadEntry $entry) {
        $ticket_id = Thread::objects()->filter([
                    'id' => $entry->getThreadId()
                ])->values_flat('object_id')->first() [0];
        // Force lookup rather than use cached data..
        // This ensures we get the full ticket, with all
        // thread entries etc.. 
        return Ticket::lookup(array(
                    'ticket_id' => $ticket_id
        ));
    }

    /**
     * Required stub.
     *
     * {@inheritdoc}
     *
     * @see Plugin::uninstall()
     */
    function uninstall(&$errors) {
        parent::uninstall ( $errors );
    }
}
