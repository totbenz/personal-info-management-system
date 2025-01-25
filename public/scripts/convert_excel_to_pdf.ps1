param (
    [string]$excelFilePath,
    [string]$pdfFilePath
)

# Create an Excel application object
$excel = New-Object -ComObject Excel.Application
$excel.Visible = $false

# Open the Excel workbook
$workbook = $excel.Workbooks.Open($excelFilePath)

# Run the macro
$excel.Run("CheckGender")

# Save the workbook as PDFd
$workbook.ExportAsFixedFormat(0, $pdfFilePath)

# Close the workbook and quit Excel
$workbook.Close($false)
$excel.Quit()

# Release the COM objects
[System.Runtime.InteropServices.Marshal]::ReleaseComObject($workbook) | Out-Null
[System.Runtime.InteropServices.Marshal]::ReleaseComObject($excel) | Out-Null
