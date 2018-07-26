<?php

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
    private $converter = 'soffice';

    /**
     * File to convert
     * @var string
     */
    private $filename;

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
     * @throws FileNotFoundException
     */
    public function __construct(string $filename)
    {
        if (! file_exists($filename)) {
            throw new FileNotFoundException('File `' . $filename . '` not found!');
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
                throw new FileNotFoundException('Directory `' . $targetDir . '` is not found');
            }
        }

        if (! is_writable($targetDir)) {
            throw new NotWritableException('Directory `' . $targetDir . '` is not writable');
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
            throw new ExecException('Converter `' . $converter . '` not found');
        }
        $this->converter = $converter;
    }

}