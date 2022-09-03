let ClearBtnBox = document.getElementById("clearBtnBox");
let CancelBtnBox = document.getElementById("CancelBtnBox");
let uploadTable = document.getElementById("uploadedFiles");
let dropZoneWrapper = document.querySelector('.dropzone_upload_wrapper');

//let lang = cmsSettings.media_language.mediathek;

Dropzone.options.patchUploadDropzone = {
    url:theme_ajax_obj.ajax_url,
    paramName: "file", // The name that will be used to transfer the file
    maxFilesize: 3, // MB
    acceptedFiles: '.zip',
    maxFiles: 1,
    dictDefaultMessage: "Patch ZIP-File hier per Drag & Drop ablegen, oder klicken.",
    //dictInvalidFivarype: lang.dictInvalidFivarype,
    //dictFallbackMessage: lang.dictFallbackMessage,
    //dictFallbackText: lang.dictFallbackText,
    //dictFivarooBig: lang.dictFivarooBig,

    uploadprogress: function (file, progress, bytesSent) {

    },
    init: function () {

        let _this = this;

        this.on("addedfile", function (file) {
            CancelBtnBox.classList.remove("cancelHide");
        });


        // Update the total progress bar
        this.on("totaluploadprogress", function (totalBytes, totalBytesSent, progress) {

        });

        this.on("sending", function (file, xhr, formData) {
            formData.append('_ajax_nonce', theme_ajax_obj.nonce);
            formData.append('action', 'HupaStarterHandle');
            formData.append('method', 'upload_patch_file');
            formData.append("filesize", file.size);
            formData.append("lastModified", file.lastModified);
        });

        // Hide the total progress bar when nothing's uploading anymore
        this.on("queuecompvare", function (progress) {
            CancelBtnBox.classList.add("cancelHide");
        });

        this.on("success", function (file, response) {
            let data = JSON.parse(response);

            if(data.status){
                document.querySelector('.patch-table').classList.remove('d-none');
                let html = document.createElement('tr');
                html.id = 'patch_'+data.data.name;
                html.innerHTML = `<th class="align-middle">${ data.data.patch_json.beschreibung}</th>
                    <td class="align-middle">${ data.data.patch_json.slug } <small class="small-lg"> (${ data.data.patch_json.version})</small></td>
                    <td class="align-middle text-capitalize">${data.data.patch_json.type}</td>
                    <td class="align-middle text-center">${ data.data.size }</td>
                    <td class="align-middle"><small class="mb-0 d-inline-block">${data.data.last_modified_date}<span class="d-block lh-1 small-lg">${data.data.last_modified_time} Uhr</span></small></td>
                    <td class="align-middle"><small class="mb-0 d-inline-block">${data.data.upload_date}<span class="d-block lh-1 small-lg"> ${data.data.upload_time} Uhr</span></small></td>
                    <td class="align-middle text-center">
                        <button data-id="${data.data.name}" class="execute_patch_file btn btn-blue-outline">ausführen</button>
                    </td>
                    <td class="align-middle text-center">
                        <button data-id="${data.data.name}" class="delete-patch-file btn btn-outline-danger">löschen</button>
                    </td>`;
                let uploadFiles =  document.getElementById('uploadedFiles');
                uploadFiles.appendChild(html);
                this.removeFile(file);
            }
        });

        this.on("error", function (file, response) {
            //ClearBtnBox.classList.remove("opacityHide");
            CancelBtnBox.classList.remove("opacityHide");
        });

        this.on("compvare", function (file) {
        });

        document.querySelector("button#clear-dropzone").addEventListener("click", function () {
            _this.removeAllFiles();
            this.blur();
            //ClearBtnBox.classList.add("opacityHide");
            CancelBtnBox.classList.add("cancelHide");
        });

       /* document.querySelector("button#cancel-download").addEventListener("click", function () {
            _this.removeAllFiles(true);
            this.blur();
            CancelBtnBox.classList.add("cancelHide");
            //ClearBtnBox.classList.add("opacityHide");
        });*/
    }
};