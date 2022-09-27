<?php
/**
 * Whoops - php errors for cool kids
 * @author Filipe Dobreira <http://github.com/filp>
 */

namespace Core\Conf\Kyaaaa\Handler\Handler;

use Core\Conf\Kyaaaa\Handler\Exception\Inspector;
use Core\Conf\Kyaaaa\Handler\RunInterface;

interface HandlerInterface
{
    /**
     * @return int|null A handler may return nothing, or a Handler::HANDLE_* constant
     */
    public function handle();

    /**
     * @param  RunInterface  $run
     * @return void
     */
    public function setRun(RunInterface $run);

    /**
     * @param  \Throwable $exception
     * @return void
     */
    public function setException($exception);

    /**
     * @param  Inspector $inspector
     * @return void
     */
    public function setInspector(Inspector $inspector);
}
