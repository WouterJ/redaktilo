<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\Redaktilo\Command;

/**
 * Thrown if the name given to CommandInvoker isn't in its collection.
 *
 * @api
 *
 * @deprecated since 1.x, use the class from the Exception namespace instead
 */
class CommandNotFoundException extends \Exception
{
    /** @var string */
    private $name;

    /** @var array */
    private $commands;

    /**
     * @param string $name
     * @param array  $commands
     */
    public function __construct($name, array $commands)
    {
        $this->name = $name;
        $this->commands = $commands;

        $message = sprintf('The command "%s" was not found in CommandInvoker', $name);

        parent::__construct($message);
    }

    /** @return string */
    public function getName()
    {
        return $this->name;
    }

    /** @return array */
    public function getCommands()
    {
        return $this->commands;
    }
}
