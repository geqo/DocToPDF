# DocToPDF
Document to PDF converter. OpenOffice or LibreOffice required.
## Installation 
```bash
composer require geqo/doctopdf
```
## Usage
```php
$file = new \Geqo\DocToPDF(__DIR__ . '/23474.csv');
$file->setTargetDir(__DIR__);
$file->execute();
```
