<?php
namespace Neducatio\ORMSessionHandlerBundle\HttpFoundation\Session\Storage\Handler;

use Neducatio\ORMSessionHandlerBundle\Model\Session as SessionEntity;

/**
 * SessionWriterInterface
 */
interface SessionWriterInterface
{
    /**
     * Used to write session for custom session entity
     *
     * @param string $sessionId     session id (as obtained from session_id() call)
     * @param string $sessionData   serialized $_SESSION global array
     * @param string $sessionEntity session entity
     */
    public function write($sessionId, $sessionData, SessionEntity $sessionEntity = null);
}
