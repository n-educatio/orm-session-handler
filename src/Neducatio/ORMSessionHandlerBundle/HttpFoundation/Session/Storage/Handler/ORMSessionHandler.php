<?php
namespace Neducatio\ORMSessionHandlerBundle\HttpFoundation\Session\Storage\Handler;

use Neducatio\ORMSessionHandlerBundle\Repository\SessionRepository;

/**
 * DoctrineORMSessionHandler
 *
 */
class ORMSessionHandler implements \SessionHandlerInterface
{
    /**
     * @var SessionRepository
     */
    private $sessionRepository;

    /**
     * @var SessionWriterInterface
     */
    private $sessionWriter;

    /**
     * Construct
     *
     * @param SessionRepository      $sessionRepository session repository
     * @param SessionWriterInterface $sessionWriter     session writer
     */
    public function __construct(SessionRepository $sessionRepository, SessionWriterInterface $sessionWriter)
    {
        $this->sessionRepository = $sessionRepository;
        $this->sessionWriter = $sessionWriter;
    }

    /**
     * {@inheritdoc}
     */
    public function open($savePath, $sessionName)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function close()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function destroy($sessionId)
    {
        $this->sessionRepository->remove(['id' => $sessionId]);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function gc($maxlifetime)
    {
        $this->sessionRepository->removeOutdated($maxlifetime);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function read($sessionId)
    {
        return $this->sessionRepository->get($sessionId)->getData();
    }

    /**
     * {@inheritdoc}
     */
    public function write($sessionId, $data)
    {
        $sessionEntity = $this->sessionRepository->get($sessionId);

        $this->sessionRepository->save($this->sessionWriter->write($sessionId, $data, $sessionEntity));

        return true;
    }
}