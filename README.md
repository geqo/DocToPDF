# DocToPDF
Document to PDF converter. OpenOffice or LibreOffice required.
## Usage
```php
$file = new \Geqo\DocToPDF(__DIR__ . '/23474.csv');
$file->setTargetDir(__DIR__);
$file->execute();
```
