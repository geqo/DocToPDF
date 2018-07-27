<?php
/*
 *
 * Copyright © 2018 Alex White geqo.ru
 * Author: Alex White
 * All rights reserved
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *    http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 */

namespace Geqo;

use Geqo\Exceptions\ExecException;
use Geqo\Exceptions\FileNotFoundException;
use Geqo\Exceptions\NotWritableException;

class DocToPDF
{
    /**
     * Converter name
     * @var string
     */
    protected $converter = 'soffice';

    /**
     * File to convert
     * @var string
     */
    protected $filename;

    /**
     * Target dir for file
     * @var string
     */
    private $targetDir;

    /**
     * Types soffice can convert
     * @var array
     */
    CONST TYPES = [
        'doc',
        'dot',
        'docx',
        'docm',
        'dotm',
        'xls',
        'xlsx',
        'wpd',
        'wps',
        'rtf',
        'txt',
        'csv',
        'sdw',
        'sgl',
        'vor',
        'xml',
        'uot',
        'uof',
        'jtd',
        'jtt',
        'hwp',
        '602',
        'pdb',
        'psw',
        'odt',
        'ott',
        'oth',
        'odm',
    ];

    /**
     * DocToPDF constructor.
     * @param string $filename
     * @param bool $try Try convert file if not supported by default
     * @throws FileNotFoundException
     * @throws \Exception
     */
    public function __construct(string $filename, bool $try = false)
    {
        if (! file_exists($filename)) {
            throw new FileNotFoundException('File `' . $filename . '` not found!');
        }

        if (! $try) {
            $extension = pathinfo($filename, PATHINFO_EXTENSION);

            if (! in_array($extension, static::TYPES)) {
                throw new \Exception('Extension does not supported, make $try `true` to ignore this message.');
            }
        }

        $this->filename = $filename;
    }

    /**
     * @return array
     * @throws ExecException
     */
    public function execute()
    {
        $command = 'export HOME=/tmp && ' . $this->converter .
            ' --headless --convert-to pdf --outdir ' . escapeshellarg($this->targetDir) .
            ' ' . escapeshellarg($this->filename) . ' 2>&1';

        exec($command, $output, $return);

        if ($return !== 0) {
            $_output = implode(' ', $output);
            throw new ExecException($_output);
        }

        return $output;
    }

    /**
     * @param string $targetDir
     * @throws FileNotFoundException
     * @throws NotWritableException
     */
    public function setTargetDir(string $targetDir)
    {
        if (! file_exists($targetDir)) {
            if (! @mkdir($targetDir)) {
                throw new FileNotFoundException('Directory `' . $targetDir . '` is not found!');
            }
        }

        if (! is_writable($targetDir)) {
            throw new NotWritableException('Directory `' . $targetDir . '` is not writable!');
        }

        $this->targetDir = $targetDir;
    }

    /**
     * @param string $converter
     * @throws ExecException
     */
    public function setConverter(string $converter)
    {
        if (stristr(`type $converter`, 'not found')) {
            throw new ExecException('Converter `' . $converter . '` not found!');
        }

        $this->converter = $converter;
    }

}