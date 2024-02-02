<?php
require_once(INCLUDE_DIR . 'class.signal.php');
require_once(INCLUDE_DIR . 'class.plugin.php');
require_once(INCLUDE_DIR . 'class.ticket.php');
require_once(INCLUDE_DIR . 'class.osticket.php');
require_once(INCLUDE_DIR . 'class.config.php');
require_once('config.php');

class StatusAutoChangePlugin extends Plugin {

    var $config_class = 'StatusAutoChangePluginConfig';
    var $backend;

    /**
     * The entrypoint of the plugin, keep short, always runs.
     */
    function bootstrap() {
        $backend = new StatusAutoChangeBackend($this->getConfig());
    }
}

/**
 * Backend class to record plugin instance config and make it accessible
 * outside of bootstrap.
 */
Class StatusAutoChangeBackend {
    var $config;
    
    function __construct($config) {
        $this->config = $config;
        
        Signal::connect('threadentry.created', function (ThreadEntry $entry) {
            $this->onTicketUpdated($entry);
        });
    }
    
    /**
     * Change status when a customer updates a ticket.
     */
    function onTicketUpdated(ThreadEntry $entry) {
        global $cfg;
        if (!$cfg instanceof OsticketConfig) {
            error_log("StatusAutoChange plugin called too early.");
            return;
        }
    
        if (!$entry instanceof MessageThreadEntry) {
            return;
        }
    
        // verify this is a ticket thread, not a task thread
        $threadType = Thread::objects()->filter([
            'id' => $entry->getThreadId()
        ])->values_flat('object_type')->first() [0];
        if ($threadType != "T") {
            return;
        }
    
        $ticket = StatusAutoChangeBackend::getTicket($entry);
        if (!$ticket instanceof Ticket) {
            return;
        }
    
        $firstEntry = $ticket->getMessages()[0];
        if ($entry->getId() == $firstEntry->getId()) {
            return;
        }
    
        $newStatus = TicketStatus::lookup($this->config->get('clientReplyStatus'));
        if (!is_null($newStatus) && $ticket->getStatusId() != $newStatus->getId()) {
            $ticket->setStatus($newStatus);
        }
    }
    
    /**
     * Fetches a ticket from a ThreadEntry.
     *
     * From https://github.com/clonemeagain/osticket-slack.
     */
    static function getTicket(ThreadEntry $entry) {
        $ticketId = Thread::objects()->filter([
            'id' => $entry->getThreadId()
        ])->values_flat('object_id')->first() [0];
        return Ticket::lookup(array('ticket_id' => $ticketId));
    }
}