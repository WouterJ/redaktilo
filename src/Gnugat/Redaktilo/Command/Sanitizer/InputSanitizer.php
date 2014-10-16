<?php

/*
 * This file is part of the Redaktilo project.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\Redaktilo\Command\Sanitizer;

/**
 * Implementations are used by Commands to sanitize the given input
 */
interface InputSanitizer
{
    /**
     * @param array $input
     *
     * @return mixed
     */
    public function sanitize(array $input);
}
