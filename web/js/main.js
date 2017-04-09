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

var Main = function() {

    var self = this;

    var authenticationsListArr = [];

    this.construct = function () {
        self.retrieveAuthentications();

        $("#registerAuthenticate").click(self.registerAuthenticate);

        $("#modal-new-authentication").click(self.showModalNewAuthentication);

        $("#connect").click(self.connect);

        var pathSelector = $("#path");

        pathSelector.val("");
        pathSelector.keydown(function (e) {
            e.which = e.which || e.keyCode;
            if (e.which == 13) {
                self.pathKeyDown();
            }
        });
    };

    this.connect = function () {
        var connectionId = $("#identifier").val();

        var buckets = self.getApiBuckets(connectionId);

        if (buckets == false) {
            alert('Could not fetch buckets! Verify that your authentications are correct.');
            return false;
        }

        $("#connectionId").val(connectionId);

        var container = $('#body-files');

        container.html("");

        var x, bucket, html = '';
        for (x in buckets) {
            bucket = buckets[x];

            html += self.buildTrFiles('bucket', bucket['name'], bucket['date']);
        }

        container.html(html);

        self.reloadClicks();

        $('#authenticate').modal('hide');
    };

    this.clickBucket = function () {
        var bucket = $(this).attr("data-file");

        $("#bucket").html(bucket);

        self.listObjects(bucket, '');

        self.reloadClicks();
    };

    this.clickFolder = function () {
        var pathSelector = $("#path");

        var path = '';

        if (pathSelector.val() != "" && pathSelector.val() != "/") {

            path = pathSelector.val();

            path = path.rtrim('/') + "/" + $(this).attr("data-file");
        } else {
            path = $(this).attr("data-file");
        }

        var bucket = $("#bucket").html();

        if (self.listObjects(bucket, path) == false) {
            return false;
        }

        pathSelector.val(path);

        self.reloadClicks();
    };

    this.clickBack = function () {
        var pathSelector = $("#path");

        var path = pathSelector.val();

        var paths = path.rtrim("/").split("/");

        paths = paths.slice(0, -1);

        path = paths.join("/");

        if (self.listObjects($("#bucket").html(), path) == false) {
            return false;
        }

        pathSelector.val(path);

        self.reloadClicks();
    };

    this.listObjects = function (bucket, path) {

        if (
            typeof bucket == "undefined"
            ||
            typeof path == "undefined"
        ) {
            return false;
        }

        var connectionId = $("#connectionId").val();

        var objects = self.getApiListObjects(connectionId, bucket, path);

        var container = $('#body-files');

        container.html("");

        var x, object, html = self.buildTrFiles('back', '..');
        for (x in objects) {
            object = objects[x];

            html += self.buildTrFiles(object['type'], object['name'], object['lastModified'], object['size']);
        }

        container.html(html);
    };

    this.pathKeyDown = function () {
        var bucket = $("#bucket").html();
        var path = $("#path");

        if (!bucket || bucket == "-") {
            path.val("");
            return false;
        }

        self.listObjects(bucket, path.val());

        self.reloadClicks();
    };

    this.reloadClicks = function () {
        $(".folder").click(self.clickFolder);
        $(".bucket").click(self.clickBucket);
        $(".back").click(self.clickBack);
    };

    this.buildTrFiles = function (type, file, lastModified, size) {

        if (typeof size == "undefined") {
            size = '-';
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
        html += "</tr>\n";
        return html;
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

    this.getApiListObjects = function (connectionId, bucket, path) {

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
            'path': path
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

    this.construct();
};

new Main();