<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * Class utilizing PHPSpreadhseet, which is most current since 2021.
 *
 * @author Stephan
 *        
 */
class OrgelbankPHPSpreadsheetWriter
{

    private $mExcel;

    public function __construct()
    {
        $this->mExcel = new Spreadsheet();
        $this->mExcel->setActiveSheetIndex(0);
        $this->mExcel->getProperties()
            ->setCreator('Stephan Watermeyer')
            ->setLastModifiedBy('Stephan Watermeyer')
            ->setTitle('Orgelbank Export')
            ->setSubject('Orgelbank Export')
            ->setDescription('Orgelbank Export.')
            ->setKeywords('Keine Schlagwörter')
            ->setCategory('Kategorie');
    }

    public function setTempDir($pDir)
    {
        // ignore
    }

    public function addWorksheet()
    {
        return $this;
    }

    public function addFormat()
    {
        return null;
    }

    public function write($pCoordinates, $pText, $pFormat = null)
    {
        $this->mExcel->getActiveSheet()->setCellValue($pCoordinates, $pText);
        
        if ($pFormat == "bold") {
            $this->mExcel->getActiveSheet()
                ->getStyle($pCoordinates)
                ->getFont()
                ->setBold(true);
        }
        
        // echo $pCoordinates . ": " . $pText . "<br>";
    }

    public function send($pName)
    {
        $objWriter = PHPExcel_IOFactory::createWriter($this->mExcel, 'Xlsx');
        $objWriter->save($pName);
    }

    public function download($pName)
    {
        return $this->downloadInner($pName);
    }

    public function downloadInner($pName)
    {
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $pName . '"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        
        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        
        $writer = IOFactory::createWriter($this->mExcel, 'Xlsx');
        $writer->save('php://output');
    }

    public function close()
    {}
}
?>
