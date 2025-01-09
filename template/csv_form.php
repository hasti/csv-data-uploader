
<h2>Upload the CSV File </h2>
<p id="show_upload_message"></p>
<form method="post" action="javascript:void(0)" enctype="multipart/form-data" id="frm_csv_data_uploader">
    <p>
        <label for="csv_data_file">Select the CSV file to upload</label>
        <input type="file" name="csv_file" accept=".csv" required>
    </p>
    
    <input type="hidden" name="action" value="csv_data_uploader">
    <input type="submit" value="Upload">
</form>