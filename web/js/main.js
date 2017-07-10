
var Main = function() {

    var self = this;

    var bucket = null;

    var authenticationsListArr = [];

    this.construct = function () {
        self.retrieveAuthentications();

        $("#registerAuthenticate").click(self.registerAuthenticate);

        $("#modal-new-authentication").click(self.showModalNewAuthentication);

        $("#connect").click(self.connect);

        $("#refresh").click(self.pathKeyDown);

        $("#link-upload").click(self.uploadModal);

        $("#load-more").click(self.loadMore);

        $("#file").change(function() {
            self.traverseFiles(this.files);
        });

        $("#return-buckets").click(function () {
            var connectionId = $("#connectionId").val();

            self.connect(connectionId);
        });

        var pathSelector = $("#path");

        pathSelector.val("");
        pathSelector.keydown(function (e) {
            e.which = e.which || e.keyCode;
            if (e.which == 13) {
                self.pathKeyDown();
            }
        });

        self.scrollBottomEvent();
    };

    this.scrollBottomEvent = function() {
        $(window).scroll(function() {
            if($(window).scrollTop() + $(window).height() == $(document).height()) {
                var loadMoreSelector = $("#load-more");
                if (loadMoreSelector.is(":visible")) {
                    loadMoreSelector.click();
                }
            }
        });
    };

    this.connect = function (connectionId) {
        self.showLoader("Connecting....");

        $("#bucket").attr("data-bucket", "").html("-");
        $("#return-buckets").hide();

        if (
            typeof connectionId == "undefined"
            ||
            typeof connectionId == "object"
        ) {
            connectionId = $("#identifier").val();
        }

        var buckets = self.getApiBuckets(connectionId);

        if (buckets == false) {
            self.hideLoader();
            alert('Could not fetch buckets! Verify that your authentications are correct.');
            return false;
        }

        $("#connectionId").val(connectionId);
        $("#path").val('');

        var container = $('#body-files');

        container.html("");

        var x, bucket, html = '';
        for (x in buckets) {
            bucket = buckets[x];

            html += self.buildTrFiles('bucket', bucket['name'], bucket['date']);
        }

        container.html(html);

        $("#connected-in").html("Connected in \"" + $("#name").val() + "\"");

        self.reloadClicks();

        self.hideLoader();

        $('#authenticate').modal('hide');
    };

    this.clickBucket = function () {
        self.showLoader();

        var bucket = $(this).attr("data-file");

        $("#bucket").attr("data-bucket", bucket).html(bucket);

        $("#return-buckets").show();

        self.listObjects(bucket, '');

        self.hideLoader();

        self.reloadClicks();
    };

    this.clickFolder = function () {
        self.showLoader();

        var path = self.buildPathFile($(this).attr("data-file"));

        var bucket = $("#bucket").attr("data-bucket");

        if (self.listObjects(bucket, path) == false) {
            self.hideLoader();
            return false;
        }

        $("#path").val(path);

        self.hideLoader();

        self.reloadClicks();
    };

    this.clickBack = function () {
        self.showLoader();

        var pathSelector = $("#path");

        var path = pathSelector.val();

        var paths = path.rtrim("/").split("/");

        paths = paths.slice(0, -1);

        path = paths.join("/");

        if (self.listObjects($("#bucket").attr("data-bucket"), path) == false) {
            self.hideLoader();
            return false;
        }

        pathSelector.val(path);

        self.hideLoader();

        self.reloadClicks();
    };

    this.clickFile = function () {
        self.showLoader("Downloading...");

        var connectionId = $("#connectionId").val();

        var path = self.buildPathFile($(this).attr("data-file"));

        var bucket = $("#bucket").attr("data-bucket");

        var file = self.getApiObject(connectionId, path, bucket);

        self.hideLoader();

        downloadURL(BASE_PATH + '/file.php?action=download&file=' + file);
    };

    this.clickDeleteFile = function() {
        var file = $(this).attr("data-file");

        if (!confirm("You are about to delete the file (" + file + "). Are you sure?")) {
            return false;
        }

        self.showLoader("Deleting...");

        var connectionId = $("#connectionId").val();
        var bucket = $("#bucket").attr("data-bucket");
        var path = self.buildPathFile($(this).attr("data-file"));


        var deleted = self.postApiDeleteObject(connectionId, bucket, path);

        self.hideLoader();

        if (deleted == true) {
            alert("Deleted successfully");
            self.pathKeyDown();
        } else {
            alert("Could not delete file");
        }

    };

    this.listObjects = function (bucket, path) {

        if (
            typeof bucket == "undefined"
            ||
            typeof path == "undefined"
        ) {
            return false;
        }

        $("#load-more")
            .attr('data-next-marker', false)
            .attr('data-bucket', false)
            .attr('data-path', false)
            .hide();

        var connectionId = $("#connectionId").val();

        var objects = self.getApiListObjects(connectionId, bucket, path);

        var container = $('#body-files');

        container.html("");

        var html = self.buildTrFiles('back', '..');

        $.each(objects['registers'], function(index, value) {
            html += self.buildTrFiles(value['type'], value['name'], value['lastModified'], value['size']);
        });

        if (objects['nextMarker']) {
            $("#load-more")
                .attr('data-next-marker', objects['nextMarker'])
                .attr('data-bucket', bucket)
                .attr('data-path', path)
                .show();
        }

        container.html(html);
    };

    this.loadMore = function() {

        self.showLoader();

        var connectionId = $("#connectionId").val();

        var objects = self.getApiListObjects(
            connectionId,
            $(this).attr('data-bucket'),
            $(this).attr('data-path'),
            $(this).attr('data-next-marker')
        );

        var container = $('#body-files');

        var html = container.html();

        $.each(objects['registers'], function(index, value) {
            html += self.buildTrFiles(value['type'], value['name'], value['lastModified'], value['size']);
        });

        $(this)
            .attr('data-next-marker', objects['nextMarker']);

        container.html(html);

        self.hideLoader();

    };

    this.pathKeyDown = function () {
        self.showLoader();

        var bucket = $("#bucket").attr("data-bucket");
        var path = $("#path");

        if (!bucket || bucket == "-") {
            self.hideLoader();
            path.val("");
            alert("You must be in some bucket!");
            return false;
        }

        self.listObjects(bucket, path.val());

        self.hideLoader();

        self.reloadClicks();
    };

    this.uploadModal = function () {

        if (!$("#connectionId").val()) {
            alert("You must be connected in to upload!");
            return false;
        }

        var bucket = $("#bucket").attr("data-bucket");

        if (!bucket) {
            alert("You must be in some bucket!");
            return false;
        }

        $("#form-upload-file").show();
        $("#form-upload-path").hide();
        $("#qty-uploaded-files").html("");
        $("#upload-bucket").html(bucket);
        $("#button-upload-file").attr("disabled", true);

        $('#upload').modal('show');
    };

    this.traverseFiles = function(files) {

        if (typeof files == "undefined") {
            return false;
        }

        var filesUploaded = [],
            fileName;

        for (var i = 0; i < files.length; i++) {
            fileName = self.uploadFileToTemp(files[i]);

            if (fileName) {
                filesUploaded.push(fileName);
            }
        }

        $("#filesUploadArr").val(JSON.stringify(filesUploaded));

        $("#qty-uploaded-files").html(filesUploaded.length + " uploaded files.");

        $("#form-upload-file").hide();
        $("#form-upload-path").show();
        $("#upload-path").val($("#path").val());

        $("#button-upload-file")
            .attr("disabled", false)
            .click(self.uploadFilesToS3);

        $("#file").val("");
    };

    this.uploadFileToTemp = function(file) {

        var formData = new FormData();
        formData.append('file', file);

        var fileName = '';

        $.ajax({
            'url': BASE_PATH + '/file.php?action=upload',
            'cache': false,
            'contentType': false,
            'processData': false,
            'data': formData,
            'type': 'POST',
            'async': false,
            'success': function(data) {
                fileName = data;
            }
        });

        return fileName;
    };

    this.uploadFilesToS3 = function () {
        self.showLoader("Uploading Files");

        var connectionId = $("#connectionId").val();
        var bucket = $("#bucket").attr("data-bucket");
        var path = $("#upload-path").val();
        var objects = JSON.parse($("#filesUploadArr").val());

        var uploaded = self.postApiCreateObject(connectionId, bucket, path, objects);

        self.hideLoader();

        if (uploaded == false) {
            alert("Could not send files");
        } else {
            $('#upload').modal('hide');
            self.pathKeyDown();
        }
    };

    this.reloadClicks = function () {
        $(".folder").click(self.clickFolder);
        $(".bucket").click(self.clickBucket);
        $(".back").click(self.clickBack);
        $(".file").click(self.clickFile);
        $(".delete-file").click(self.clickDeleteFile);
    };

    this.buildTrFiles = function (type, file, lastModified, size) {

        if (typeof size == "undefined") {
            size = '-';
        } else {
            size = filesize(size);
        }

        if (typeof lastModified == "undefined") {
            lastModified = '-';
        }

        var html = "<tr>\n";
        html += "<td>-</td>\n";

        var image = '', classStr = '';
        switch (type) {
            case 'bucket':
                image = 'bucket.png';
                classStr = 'bucket';
                break;
            case 'folder':
                image = 'folder.png';
                classStr = 'folder';
                break;
            case 'back':
                image = 'back.png';
                classStr = 'back';
                break;
            default:
                image = 'file.png';
                classStr = 'file';
        }

        image = '<img src="web/img/' + image + '">';

        html += "<td><a href='#' class='" + classStr + "' data-file='" + file + "'>" + image + " " + file + "</a></td>\n";
        html += "<td>" + lastModified + "</td>\n";
        html += "<td>" + size + "</td>\n";

        var actions = '';
        if (type == 'file') {
            actions = self.buildActionsDropdown(type + '-' + file, file);
        }

        html += "<td>" + actions + "</td>\n";

        html += "</tr>\n";
        return html;
    };

    this.buildActionsDropdown = function (id, file) {
        return '<div class="dropdown">' +
            '<button class="btn btn-default btn-xs dropdown-toggle" type="button" id="' + id + '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">' +
            'Actions ' +
            '<span class="caret"></span>' +
            '</button>' +
            '<ul class="dropdown-menu" aria-labelledby="' + id + '"> ' +
            '<li><a href="#" class="delete-file" data-file="' + file + '">Delete</a></li> ' +
            '</ul> </div>';
    };

    this.buildPathFile = function (file) {
        if (typeof file == "undefined") {
            return false;
        }

        var pathSelector = $("#path"),
            path = file;

        if (pathSelector.val() != "" && pathSelector.val() != "/") {

            path = pathSelector.val();

            path = path.rtrim('/') + "/" + file;
        }

        return path;
    };

    this.showLoader = function(message) {
        $("#loader").show();
        $("#loader-message").html(message);
    };

    this.hideLoader = function() {
        $("#loader").hide();
        $("#loader-message").html("");
    };

    this.showModalNewAuthentication = function () {
        $("#name").val('');
        $("#key").val('');
        $("#secret").val('');
        $("#identifier").val('');
        $("#region").val('');

        $("#connect").hide();

        $('#authenticate').modal('show');
    };

    this.registerAuthenticate = function () {
        var name = $("#name"),
            key = $("#key"),
            secret = $("#secret"),
            identifier = $("#identifier"),
            region = $("#region");

        if (!name.val()) {
            alert("Name is required!");
            name.focus();
            return false;
        }

        if (!key.val()) {
            alert("Key is required!");
            key.focus();
            return false;
        }

        if (!secret.val()) {
            alert("Secret is required!");
            secret.focus();
            return false;
        }

        var dataSend = {
            'name': name.val(),
            'identifier': identifier.val(),
            'key': key.val(),
            'secret': secret.val(),
            'region': region.val()
        };

        if (self.postApiAuthentication(dataSend) == false) {
            alert("Could not save");
            return false;
        }

        var authentications = self.getApiAuthentications();
        var selector = '#dropdown-authentications';
        var container = $(selector).html();

        var authentication = authentications[authentications.length - 1];

        var x, updateHtml = true;
        for (x in authenticationsListArr) {
            if (authenticationsListArr[x] == authentication['identifier']) {
                updateHtml = false;
            }
        }

        if (updateHtml == true) {
            authenticationsListArr.push(authentication['identifier']);

            container += '<li><a href="#" class="show-authenticate" data-identifier="' + authentication['identifier'] + '">' + authentication['name'] + '</a></li>';

            $(selector).html(container);
        }

        $(".show-authenticate").click(self.showAuthentication);

        alert("Saved successfully");

    };

    this.retrieveAuthentications = function() {

        var selector = '#dropdown-authentications';
        var container = $(selector).html();

        var authentications = self.getApiAuthentications(), x, val;

        for (x in authentications) {
            val = authentications[x];
            container += '<li><a href="#" class="show-authenticate" data-identifier="' + val['identifier'] + '">' + val['name'] + '</a></li>';

            authenticationsListArr.push(val['identifier']);
        }

        $(selector).html(container);

        $(".show-authenticate").click(self.showAuthentication);
    };

    this.showAuthentication = function () {
        var identifier = $(this).attr("data-identifier");

        var authenticate = self.getApiAuthentications(identifier);

        $("#name").val(authenticate['name']);
        $("#key").val(authenticate['key']);
        $("#secret").val(authenticate['secret']);
        $("#region").val(authenticate['region']);
        $("#identifier").val(identifier);

        $("#connect").show();

        $('#authenticate').modal('show');

    };

    this.getApiAuthentications = function (identifier) {

        if (typeof identifier === "undefined") {
            identifier = null;
        }

        var data = null;

        $.ajax({
            'url': BASE_PATH + '/api/authentications',
            'type': 'GET',
            'async': false,
            'dataType': 'json',
            'success': function (ret) {
                data = ret;

                if (identifier != null) {
                    data = null;
                    var x;
                    for (x in ret) {
                        if (ret[x]['identifier'] == identifier) {
                            data = ret[x];
                            break;
                        }
                    }
                }
            },
            'error': function () {

            }
        });

        return data;
    };

    this.postApiAuthentication = function (dataSend) {

        if (typeof dataSend != "object") {
            return false;
        }

        var success = false;

        $.ajax({
            'url': BASE_PATH + '/api/authentications',
            'dataType': 'json',
            'type': 'POST',
            'data': JSON.stringify(dataSend),
            'contentType': 'application/json',
            'async': false,
            'success': function () {
                success = true;
            },
            'error': function (xhr) {
                if (xhr.status == 200) {
                    success = true;
                }
            }
        });

        return success;
    };

    this.getApiBuckets = function (connectionId) {
        if (typeof connectionId == "undefined") {
            return false;
        }

        var buckets = false;

        $.ajax({
            'url': BASE_PATH + '/api/buckets/' + connectionId,
            'type': 'GET',
            'async': false,
            'dataType': 'json',
            'success': function (data) {
                buckets = data;
            }
        });

        return buckets;
    };

    this.getApiListObjects = function (connectionId, bucket, path, marker) {

        if (
            typeof connectionId == "undefined"
            ||
            typeof bucket == "undefined"
        ) {
            return false;
        }

        if (typeof path == "undefined" || path == '/') {
            path = '';
        }

        var dataSend = {
            'bucket': bucket,
            'path': path,
            'marker': marker
        };

        var objects = null;

        $.ajax({
            'url': BASE_PATH + '/api/objects/list/' + connectionId,
            'dataType': 'json',
            'type': 'POST',
            'data': JSON.stringify(dataSend),
            'contentType': 'application/json',
            'async': false,
            'success': function (data) {
                objects = data;
            },
            'error': function (xhr) {

            }
        });

        return objects;

    };

    this.getApiObject = function (connectionId, object, bucket) {
        if (
            typeof connectionId == "undefined"
            ||
            typeof object == "undefined"
            ||
            typeof bucket == "undefined"
        ) {
            return false;
        }

        var dataSend = {
            'file': object,
            'bucket': bucket
        };

        var apiReturn = null;

        $.ajax({
            'url': BASE_PATH + '/api/objects/retrieve/' + connectionId,
            'dataType': 'json',
            'type': 'POST',
            'data': JSON.stringify(dataSend),
            'contentType': 'application/json',
            'async': false,
            'success': function (data) {
                apiReturn = data;
            },
            'error': function (xhr) {
            }
        });

        return apiReturn['file'];
    };

    this.postApiCreateObject = function(connectionId, bucket, path, objects) {
        if (
            typeof connectionId == "undefined"
            ||
            typeof path == "undefined"
            ||
            typeof bucket == "undefined"
            ||
            typeof objects == "undefined"
        ) {
            return false;
        }

        var dataSend = {
            'path': path,
            'bucket': bucket,
            'files': objects
        };

        var success = false;

        $.ajax({
            'url': BASE_PATH + '/api/objects/create/' + connectionId,
            'dataType': 'json',
            'type': 'POST',
            'data': JSON.stringify(dataSend),
            'contentType': 'application/json',
            'async': false,
            'success': function (data) {
                success = true;
            },
            'error': function (xhr) {
                console.log(xhr.responseText);
                if (xhr.status == 200) {
                    success = true;
                }
            }
        });

        return success;
    };

    this.postApiDeleteObject = function(connectionId, bucket, file) {
        if (
            typeof connectionId == "undefined"
            ||
            typeof file == "undefined"
            ||
            typeof bucket == "undefined"
        ) {
            return false;
        }

        var dataSend = {
            'bucket': bucket,
            'file': file
        };

        var success = false;

        $.ajax({
            'url': BASE_PATH + '/api/objects/delete/' + connectionId,
            'dataType': 'json',
            'type': 'POST',
            'data': JSON.stringify(dataSend),
            'contentType': 'application/json',
            'async': false,
            'success': function () {
                success = true;
            },
            'error': function (xhr) {
                if (xhr.status == 200) {
                    success = true;
                }
            }
        });

        return success;
    };

    this.construct();
};

String.prototype.rtrim = function (s) {
    if (s == undefined)
        s = '\\s';
    return this.replace(new RegExp("[" + s + "]*$"), '');
};
String.prototype.ltrim = function (s) {
    if (s == undefined)
        s = '\\s';
    return this.replace(new RegExp("^[" + s + "]*"), '');
};

function downloadURL(url) {
    var hiddenIFrameID = 'hiddenDownloader',
        iframe = document.getElementById(hiddenIFrameID);
    if (iframe === null) {
        iframe = document.createElement('iframe');
        iframe.id = hiddenIFrameID;
        iframe.style.display = 'none';
        document.body.appendChild(iframe);
    }
    iframe.src = url;
}

function clearForm() {
    $(':input').not(':button, :submit, :reset, :checkbox, :radio').val('');
    $(':checkbox, :radio').prop('checked', false);
}

clearForm();

new Main();