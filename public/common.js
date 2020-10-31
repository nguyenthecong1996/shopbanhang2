window._config = {
  getZipCodeApi: "http://zipcoda.net/api",
  getMemberList: "/admin/members",
  detailMemberList: "/admin/members/show",
  getAutoMemberCd: "getAutoMemberCd",
};

moment.suppressDeprecationWarnings = true;
// var kuroshiro = new Kuroshiro();
// kuroshiro.init(new KuromojiAnalyzer({ dictPath: '/libs/kuroshiro/dict' }));
window._state = {
  addUrlParam: function (key, value){
    if(!value) value = '';
    key = encodeURI(key); value = encodeURI(value);

    var kvp = document.location.search.substr(1).split('&');

    var i=kvp.length; var x; while(i--)
    {
        x = kvp[i].split('=');

        if (x[0]==key)
        {
            x[1] = value;
            kvp[i] = x.join('=');
            break;
        }
    }

    if(i<0) {
      kvp[kvp.length] = [key,value].join('=');
    }

    var str = ""
    if(!kvp[0]) {
      str = kvp.join('');
    } else {
      str = kvp.join('&');
    }
    document.location.search = str;
  },
  getUrlParamByKey: function(key) {
    var listParams = {};
    var kvp = document.location.search.substr(1).split('&');
    var i, child;

    for(i in kvp) {
      child = kvp[i].split('=');
      if(child.length >=2) {
        listParams[child[0]] = child[1];
      }
    }
    return listParams[key];
  },
  getAllUrlParams: function () {
    var listParams = {};
    var kvp = document.location.search.substr(1).split('&');
    var i, child;

    for(i in kvp) {
      child = kvp[i].split('=');
      if(child.length >=2) {
        listParams[child[0]] = child[1];
      }
    }
    return listParams;
  },
  convertToUrlParams: function(obj, encode) {
    var str = "", i;
    for(i in obj) {
      if(str) str += "&";
      str += i + "=" + obj[i];
    }
    if(encode) {
      str = encodeURI(str);
    }
    return str;
  }
}

window._cookie = {
  get: function(key) {
    var data = "";
    if(document.cookie){
      var cookie = document.cookie.split(';');
      if(cookie && cookie.length > 0){
        for(var i in cookie){
          var childOfCookie = null;
          childOfCookie = cookie[i].split('=');
          if(childOfCookie && childOfCookie.length > 0 && childOfCookie[0] && childOfCookie[0].trim() == key){
            data = childOfCookie[1];
          }
        }
      }
    }

    return data;
  },
  set: function(key, value, exp) {
    var data = key + "=" + value;
    if(typeof exp === 'string') {
      data += "; expires=" + exp;
    }
    document.cookie = data;
  },
  remove: function() {

  }
}

window._common = {
  randomNumber: function() {
    return Math.floor(new Date().getTime() / 1000).toString() + Math.floor(Math.random() * (9999-1000) + 1000);
  },
  getFormParams: function (selector) {
    var paramArray = selector.serializeArray();
    var params = {}, i;
    for(i in paramArray) {
      params[paramArray[i]['name']] = paramArray[i]['value'];
    }
    return params;
  },
  request: function(url, data, option) {
    if(!option) option = {};
    if(!data) data = {};
    if(!option.disable_loading) {
      document.body.style.cursor='wait';
      $('loading').show();
    }

    // block submit button
    $('.submit-button').addClass('disable-click', true);

    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    return new Promise(function(resolve, reject){
      var ajaxRequest = {
        headers: {},
        type: (option.method || "get").toLowerCase(),
        url: url,
        data: data,
        success: function(response){
          $('loading').hide();
          $('.submit-button').removeClass('disable-click', true);
          document.body.style.cursor = 'default';
          return resolve(response);
        },
        error: function(jqXHR, textStatus, errorThrown) {
          if(jqXHR) {
              jqXHR.textStatus = textStatus;
              jqXHR.errorThrown = errorThrown;
              if(jqXHR.status == 401) {
                window.location.href = $server_routes['admin.manager'];
              }
          }
          _common.showErrorMessage(jqXHR, option.selector);
          $('loading').hide();
          $('.submit-button').removeClass('disable-click', true);
          document.body.style.cursor = 'default';
          return reject(jqXHR);
        }
      };

      var i;
      for(i in option) {
        ajaxRequest[i] = option[i];
      }

      $.ajax(ajaxRequest);
    });
  },
  showErrorMessage: function (jqXHR, selector) {
    var obj = JSON.parse(jqXHR.responseText)['errors'];
    var key;
    if(selector) {
      selector.find('common-error').remove();
      for (key in obj) {
        $('<common-error>' + obj[key].join('. ') + '</common-error>')
          .insertAfter(selector.find('[name="' + key + '"]'));
      }
    }
  },
  /**
   * Load ajax html
   * @param  {[type]}   url        url request
   * @param  {[type]}   idSelector id is scope
   * @param  {[type]}   params     params want to add to request
   * @param  {Function} cb         after load html
   */
  ajaxViewer: function(url, idSelector, params, cb) {
    $('loading').show();
    if(!params || typeof params !== 'object') params = {};
    params.ajaxViewer = '1';
    var i;
    // Input, textarea not radio
    $(".ajaxViewer#" + idSelector + " input[type!='radio'], .ajaxViewer#" + idSelector + " textarea")
    .each(function(){
      var thisSelector = $(this);
      var attr = thisSelector.attr('name');
      var value = thisSelector.val();
      params[attr] = value;
    });
    // input for checkbox , radio
    $(".ajaxViewer#" + idSelector + " input:checked").each(function(){
      var thisSelector = $(this);
      var attr = thisSelector.attr('name');
      var value = thisSelector.val();
      params[attr] = value;
    });

    paramsUrl = _state.convertToUrlParams(params, 'encode');
    $(".ajaxViewer#" + idSelector).load(url + '?' + paramsUrl, function(){
      // set old search state
      for(i in params) {
        $("#"+ idSelector + " input[name='" + i + "'][type!='radio'][type!='checkbox']").val(params[i]);
        $("#"+ idSelector + " textarea[name='" + i + "']").val(params[i]);
        $("#"+ idSelector + " input[type='radio'][name='" + i + "'][value='" + params[i] + "']").prop('checked', true);
        $("#"+ idSelector + " input[type='checkbox'][name='" + i + "'][value='" + params[i] + "']").prop('checked', true);
      }
      $('loading').hide();
      if(typeof cb === 'function') cb();
    });
  },
  removeComma: function(data) {
    if(!data) data = "";
    return data.replace(/,/g, '');
  },
  numberWithCommas: function(x) {
    if(!x) x = 0;
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  },
  formatNumber: function(x) {
    if(!x) x = 0;
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  },
  formatCurrency: function(x) {
    if(!x) x = 0;
    return  '￥' + x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  },
  addInputMaskCurrency: function(selector) {
    selector.inputmask({
      'alias': 'decimal',
      'groupSeparator': ',',
      'autoGroup': true,
      'removeMaskOnSubmit': true,
      'autoUnmask': true,
      'prefix': '￥',
      'definitions': {
        '~': {
          'validator': '[0-9０-９]*'
        }
      },
      // onBeforeMask: function (value, opts) {
      //   // Defined this to fix bug of lib
      // }
    });
  },
  addInputMaskNumber: function(selector) {
    selector.inputmask({
      'alias': 'decimal',
      'groupSeparator': ',',
      'autoGroup': true,
      'removeMaskOnSubmit': true,
      'autoUnmask': true,
      'definitions': {
        '~': {
          'validator': '[0-9０-９]*'
        }
      },
      // onBeforeMask: function (value, opts) {
      //   // Defined this to fix bug of lib
      // }
    });
  },
  getAddress: function(selector) {
    var data = {};
    var address = $(selector).find('[prefectures]').val() +
    $(selector).find('[address1]').val() +
    $(selector).find('[address2]').val();
    var zipcode = $(selector).find('[zip_code]').val();

    if(zipcode) {
      data['zipcode'] = zipcode;
    } else {
      data['address'] = address;
    }

    _common.request($server_routes['admin.getAddressByZipCode'], data)
    .then(function(result){
      if(result) {
        var addressInfo = result;
        if(!zipcode) {
          selector.find('[zip_code]').val(addressInfo.zipcode).change();
        } else {
          selector.find('[prefectures]').val(addressInfo.prefectures).change();
          selector.find('[address1]').val(addressInfo.address1).keyup();
          selector.find('[address1_kana]').val(addressInfo.address1_kana).keyup();
          selector.find('[address2]').val(addressInfo.address2).keyup();;
          selector.find('[address2_kana]').val(addressInfo.address2_kana).keyup();;
        }
        if(typeof calcDeliveryFee == 'function') {
            calcDeliveryFee();
        }
      }
    })
    .catch(function(err){
      console.log(err);
    })
  },

  getAutoMemberCd: function() {
  _common.request(_config.getAutoMemberCd)
    .then(function(result){
      $('[name="member_cd"]').val(result);
    })
    .catch(function(err){
      console.log(err);
    })
  },

  addUrlParamToPDFLink: function () {
    $('.print-pdf-link').each(function () {
      var thisSelector = $(this);
      var allUrlParams = _state.getAllUrlParams();
      var currentHref = thisSelector.attr('href');
      thisSelector.attr('href', currentHref + "?" + _state.convertToUrlParams(allUrlParams));
    });
  },
  addParamToPDFLink: function (key, value) {
    $('.print-pdf-link').each(function () {
      var thisSelector = $(this);
      var currentUrl = thisSelector.attr('href');
      var i;
      var currentUrlArray = currentUrl.split('?');
      var currentDomain = currentUrlArray[0];
      var currentHref = currentUrlArray[1] || '';

      // Remove duplicate key
      var currentHrefArray = currentHref.split('&');
      if(currentHrefArray.length > 0) {
        for(i in currentHrefArray) {
          if(currentHrefArray[i].split('=')[0] == key) {
            var index = currentHrefArray.indexOf(currentHrefArray[i]);
            if (index > -1) {
              currentHrefArray.splice(index, 1);
            }
          }
        }
        currentHref = currentHrefArray.join('&');
      }

      currentUrl = currentDomain;

      if(currentHref) {
        currentUrl +=  "?" + currentHref;
      }

      if(currentUrl.indexOf('?') > 1) {
        thisSelector.attr('href', currentUrl + '&' + key + '=' + value);
      } else {
        thisSelector.attr('href', '?' + key + '=' + value);
      }
    });
  },
  removeArrayItem: function(value, array) {
    var index = array.indexOf(value);
    if (index > -1) {
      array.splice(index, 1);
    }
    return array;
  },
  changeFullWidthZipCode: function(string) {
    string = string.replace(/[Ａ-Ｚａ-ｚ０-９]/g, function(s) {
      return String.fromCharCode(s.charCodeAt(0) - 0xFEE0);
    });
    string = string.replace(/\_|\-/g, '');
    return string;
  },
  changeFullWidthNumber: function(string) {
    if(string == undefined || string == null) {
      string = '';
    }
    string = string.toString().replace(/[０-９]/g, function(s) {
      return String.fromCharCode(s.charCodeAt(0) - 0xFEE0);
    });
    string = string.replace(/\。/, '.');
    string = string.replace(/\￥|\,/, '');
    string = string.replace(/\ー/, '-');
    return string;
  },

  changeFullWidthKana: function(string) {
    if(string == undefined || string == null) {
      string = '';
    }
    //Convert kana character
    var smallKana = $server_common['smallKana'];
    var bigKana   = $server_common['bigKana'];
    var convertKana = '';
    for (var i = 0; i < string.length; i++){
      var index = jQuery.inArray(string.charAt(i), bigKana);
      if (index !== -1) {
        convertKana+= smallKana[index];
      }
      else
      {
        convertKana+= string.charAt(i);
      }
    }

    //Convert text and number to half-width
    result = convertKana.toString().replace(/[Ａ-Ｚａ-ｚ０-９]/g, function(s) {
      return String.fromCharCode(s.charCodeAt(0) - 0xFEE0)});
    result = result.replace(/\。/, '.');
    result = result.replace(/\￥|\,/, '');
    return result;
  },

  objectMerge: function(thisObject, newObject, replaceFlag){
    var i;
    for(i in newObject) {
      if(replaceFlag || typeof thisObject[i] == 'undefined') {
        thisObject[i] = newObject[i];
      }
    }
  },
  checkRequired: function(thisSelector) {
    if(!thisSelector.val()) {
      thisSelector.addClass('bg-required');
    } else {
      thisSelector.removeClass('bg-required');
    }
  },
  buildTable: function(props) {
    var tableSelector = props.selector;
    if(!tableSelector) {
      console.error('Table selector is not exist');
      return;
    }
    var i, column_name, template = [], obj, clickEvent;
    var lastIndex = 0;
    if(props.build_mode == 'append' || props.build_mode == 'next_page') {
      var lastIndexSelector = tableSelector.find('tr').last();
      if(lastIndexSelector) {
        lastIndex = (+lastIndexSelector.attr('data-index') || 0) + 1;
      }
    }

    if(typeof props.data !== 'object') return false;
    for(i in props.data) {
      obj = _.clone(props.data[i]);
      template.push('<tr data-index="' + (lastIndex + (+i)) + '"');
      clickEvent = 'ondblclick';
      if(props.select_multi) {
        clickEvent = 'onclick';
      } else {
        template.push('onclick="_common.onClickRow(this, \'' + props.ctrl_name + '\')"');
      }
      if(props.ctrl_name && typeof window[props.ctrl_name]['select_row'] == 'function') {
        template.push(clickEvent + '="_common.onSelectRow(this,\'' + props.ctrl_name + '\'); ' + props.ctrl_name + '.select_row(this);"');
      }
      template.push('>');
      tableSelector.find('th').each(function (e) {
        thisSelector = $(this);
        column_name = thisSelector.attr('column-name');
        // Type
        var options = {
          index: (lastIndex + (+i)),
          col_type: thisSelector.attr('col-type'),
          col_class: thisSelector.attr('col-class'),
          column_name: column_name,
          col_input: thisSelector.attr('col-input'),
          col_format: thisSelector.attr('col-format'),
        };

        template.push('<td');
        if(options.col_class) {
          template.push(' class="' + options.col_class + '"');
        }
        template.push('>');
          template.push(_common.generateType(obj[column_name], options)
          );
        template.push('</td>');
      });
      template.push('</tr>');
    }
    if(props.build_mode == 'append' || props.build_mode == 'next_page') {
      tableSelector.find('tbody').append(template.join(''));
    } else {
      tableSelector.find('tbody').html(template.join(''));
    }
  },
  generateType: function(data, options) {
    var return_data = "";
    if([null, undefined].indexOf(data) > -1) {
      return return_data;
    }

    if(trans.common[options.column_name + '_option']) {
      return_data = '<text>' + trans.common[options.column_name + '_option'][data] + '</text>';
    } else {
      switch(options.col_type){
        case 'number':
          return_data = '<text>' + _common.formatNumber(data) + '</text>';
          break;
        case 'date':
          if(!options.col_format) {
            options.col_format = 'YYYY/MM/DD';
          }
          return_data = '<text>' + moment(data).format(options.col_format) + '</text>';
          break;
        default:
          return_data = '<text>' + data + '</text>';
      }
    }


    if(options.col_input) {
      return_data += '<input type="hidden" col-name="' + options.column_name + '" name="' +
        options.col_input + '[' + options.index + '][' + options.column_name +']" value="' + data + '">';
    }
    return return_data;
  },
  onSelectRow: function(thisSelector, ctrl_name) {
    window[ctrl_name]['selector'].find('.common-select-button').prop('disabled', false);
  },
  onClickRow: function(thisSelector, ctrl_name) {
    thisSelector = $(thisSelector);
    thisSelector.closest('table').find('tr').removeClass('active-row');
    thisSelector.addClass('active-row');
    if(!window[ctrl_name].state) {
      window[ctrl_name].state = {};
    }
    window[ctrl_name].state.currentSelector = thisSelector;
    window[ctrl_name]['selector'].find('.common-select-button').prop('disabled', false);
  },
  print: function(props) {
    if(!props) {
      return false;
    }
    if(!props.type) props.type = 'html';

    if (!props.maxWidth) props.maxWidth = 800;

    $('#common-print').html(props.html);
    setTimeout(function(){
      printJS({
        printable: 'common-print',
        type: props.type,
        css: [$env.origin + '/css/admin/partials/print.css'],
        style: '*',
        maxWidth: props.maxWidth
      });
      // $('#common-html').empty();
    }, 1000);
    return true;
  },
  buildListOption: function(props) {
    if(!props) {
      return false;
    }
    var data = props.data;
    var i, j, getdata = {}, tmp = [];
    tmp.push('<option value></option>');

    if(!props.except) props.except = {};
    for(i in data) {
      // Except column handle
      isRemove = false;
      for(j in props.except) {
        if(data[i][j] == props.except[j]) {
          isRemove = true;
          break;
        }
      }
      if(isRemove) continue;

      tmp.push('<option value="' + data[i][props.value] + '"');
      tmp.push('>');
        tmp.push(data[i][props.text]);
      tmp.push('</option>');
    }
    props.selector.html(tmp.join(''));
  },
  buildUploadError: function(errors, selector) {
    if(!errors || typeof errors !== 'object') return;
    var i, j, template = [];
    for(i in errors) {
      // File bug
      if(typeof errors[i] == 'string') {
        template.push(errors[i]);
        continue;
      }
      // Data bug
      for(j in errors[i]) {
        template.push('<div>');
          template.push(trans.common.upload.row + i + ': ');
          template.push(errors[i][j].join(', '));
        template.push('</div>');
      }
    }
    selector.find('.common-upload-error').html(
      '<common-error>' +
      template.join(' ') +
      '</common-error>'
    );
  }
};

window._modal = {
  confirm: function(props) {
    if(!props) props = {};
    if(props.title) $('[modal="confirm"] .modal-title').html(props.title);
    if(props.body) $('[modal="confirm"] .modal-body').html(props.body);
    var click_temp = 'window._modal.callback();';
    if(!props.disable_close) {
      click_temp += ' _modal.close();';
    }
    $('[modal="confirm"] [modal-confirm="submit"]').attr('onclick', click_temp);
    if(props.okButton) {
      $('[modal="confirm"] [modal-confirm="submit"]').html(props.okButton);
    }
    if(props.closeButton) {
      $('[modal="confirm"] [modal-confirm="close"]').html(props.closeButton);
    }

    if(props.hideOkButton) {
      $('[modal="confirm"] [modal-confirm="submit"]').hide();
    } else {
      $('[modal="confirm"] [modal-confirm="submit"]').show();
    }

    if(props.hideCloseButton) {
      $('[modal="confirm"] [modal-confirm="close"]').hide();
    } else {
      $('[modal="confirm"] [modal-confirm="close"]').show();
    }

    $('[modal="confirm"]').modal({show: true});
    if(props.callback) {
      window._modal.callback = props.callback;
    }
  },
  close: function() {
    $('[modal="confirm"]').modal('hide');
  },
  closeAll: function() {
   $('.modal').modal('hide');
  }
};

window._upload = {
  buildImage: function(thisSelector, data) {
    var findParent = thisSelector.parent();
    findParent.find('[source]').css({
      'background': 'url(' + data + ')',
      'background-repeat': 'no-repeat',
      'background-position': 'center',
      'background-size': 'contain'
    });
    findParent.find('.upload-image-delete-button').show();
    findParent.find('.upload-image-text').hide();
  },
  setFileData: function(files, thisSelector) {
    if(files && files[0]) {
      files = files[0];
    }

    var findParent = thisSelector.parent();
    if (files) {
      var reader = new FileReader();

      reader.onload = function(e) {
        var result = e.target.result;
        findParent.find('.upload-image-input-cache').val(result);
        _upload.buildImage(thisSelector, result);
      }

      reader.readAsDataURL(files);
    }
  }
}

$(document).ready(function(){
  //set autocomplete off
  $('input.form-control').attr('autocomplete','off')

  // Common class
  // zip code input mask
  $(document).on('change', '.zip-code-input', function(){
    var thisSelector = $(this);
    var value = thisSelector.val() || '';
    value = _common.changeFullWidthNumber(value.replace(/[^0-9０-９]/g, '').slice(0, 7));
    value = value.replace(/(\d{3})(?=\d)/, '$1-');
    thisSelector.val(value);
  });

  $(document).find('.common-currency').inputmask({
    'alias': 'decimal',
    'groupSeparator': ',',
    'autoGroup': true,
    'removeMaskOnSubmit': true,
    'autoUnmask': true,
    'prefix': '￥',
    'definitions': {
      '~': {
        'validator': '[0-9０-９]*'
      }
    },
    // onBeforeMask: function (value, opts) {
    //   // Defined this to fix bug of lib, auto remove dot in decimal
    // }
  });

  $(document).find('.common-number').inputmask({
    'alias': 'decimal',
    'groupSeparator': ',',
    'autoGroup': true,
    'removeMaskOnSubmit': true,
    'autoUnmask': true,
    'definitions': {
      '~': {
        'validator': '[0-9０-９]*'
      }
    },
    // onBeforeMask: function (value, opts) {
    //   // Defined this to fix bug of lib, auto remove dot in decimal
    // }
  });

  // handle type number fullwidth
  $(document).on('focusout', '.common-number, .common-currency', function(e){
    var thisSelector = $(this);
    thisSelector.val(_common.changeFullWidthNumber(thisSelector.val()));
    if(thisSelector.hasClass('common-number')) {
      _common.addInputMaskNumber(thisSelector);
    } else {
      _common.addInputMaskCurrency(thisSelector);
    }
    thisSelector.trigger('keyup');
  });

  $(document).on('focusin', '.common-number, .common-currency', function(e){
    var thisSelector = $(this);
    var value = thisSelector.inputmask('unmaskedvalue');
    thisSelector.inputmask('remove');
    thisSelector.val(value);
  });

  // Init Common datepicker using .common-datepicker
  $('.common-datepicker').each(function(e){
    var thisSelector = $(this);
    thisSelector.attr("autocomplete", "off");

    var format = $(this).attr('format') || 'yyyy/mm/dd';
    var dateOption = {
      format: format,
      language: 'ja',
      autoclose: true,
      orientation: 'auto'
    }
    if(thisSelector.attr('maxDate') == 'now') {
      dateOption['endDate'] = '+0d';
    }

    if(thisSelector.attr('minDate') == 'now') {
      dateOption['startDate'] = '+0d';
    }

    $(this).datepicker(dateOption)
      .on('show', function(e) {
        var thisSelector = $(this)

        if(!thisSelector.val()){
          $('.datepicker td').removeClass('active');
          var current_time = moment().startOf('days').add(moment().utcOffset(), 'minutes').valueOf();

          var date_time = $('.datepicker td[data-date="' +  current_time + '"]').addClass('active');
        }
      });
  });

  // common-timepicker
  $('.common-timepicker').each(function(){
    $(this).timepicker({
      minuteStep: 1,
      appendWidgetTo: 'body',
      showMeridian: false,
      defaultTime: false
    });
  });

  // Upload input drop
  $('.upload-image-background').on({
    drop: function(e) {
      e.stopPropagation();
      e.preventDefault();
      if(e.originalEvent.dataTransfer){
        var files = e.originalEvent.dataTransfer.files;
        window._upload.setFileData(files, $(this));
      }
    }
  });

  // upload by input
  $('.upload-image-input').change(function(){
    var thisSelector = $(this);
    var files =  thisSelector.prop('files');
    window._upload.setFileData(files, thisSelector);
  });

  $('.upload-image-input-cache').each(function(e){
    var thisSelector = $(this);
    var cacheValue = thisSelector.val();
    if(cacheValue) {
      _upload.buildImage(thisSelector, cacheValue);
    }
  });

  $('.upload-image-delete-button').click(function(){
    var thisSelector = $(this);
    thisSelector.hide();
    var findParent = thisSelector.parent();
    findParent.find('.upload-image-text').show();
    findParent.find('.upload-image-content').attr('style', '');
    findParent.find('.upload-image-input-cache').val('');
    findParent.find('.upload-image-input').val('');
  });

  // show image by url
  $('.upload-image-content').each(function(){
    var thisSelector = $(this);
    var source_url = thisSelector.attr('source_url');
    if(source_url) {
      var xhr = new XMLHttpRequest();
      xhr.onload = function() {
        window._upload.setFileData(xhr.response, thisSelector.parent());
      }
      xhr.open('GET', source_url);
      xhr.responseType = 'blob';
      xhr.send();
    }
  });

  // block ui
  // remove block ui
  $('loading').hide();
  // add block ui
  $('.click-show-loading').click(function(){
    $('loading').show();
  });

  // common toggle
  $('.common-toggle-content').each(function(){
    var thisSelector = $(this);
    var code = thisSelector.attr('code');
    if(!code) {
      alert("code attribute is require in .common-toggle-content");
    }
    var value = parseInt(_state.getUrlParamByKey(code)) || 0;
    if(typeof req != 'undefined' && req && req[code]) {
      value = req[code];
    }

    if(value == 1) {
      thisSelector.show();
      $('.common-toggle-click[code="' + code + '"]').prepend('<i class="fa fa-minus"></i>');
    } else {
      thisSelector.hide();
      $('.common-toggle-click[code="' + code + '"]').prepend('<i class="fa fa-plus"></i>');
    }

    thisSelector.prepend('<input type="hidden" name="' + code + '" code="' + code + '" value="' + value + '">');
  });

  $('.common-toggle-click').click(function(){
    var thisSelector = $(this);
    var code = $(this).attr('code');
    var thisIcon = thisSelector.find('fa');
    var commonToggleContent = $(document).find('.common-toggle-content[code=' + code + ']');
    var value = 0;
    var icon = 'fa-plus';
    commonToggleContent.toggle();

    if(commonToggleContent.css('display') != 'none') {
      value = 1;
      icon = 'fa-minus';
    }

    $('.common-toggle-click[code="' + code + '"] i')
      .removeClass('fa-plus')
      .removeClass('fa-minus')
      .addClass(icon);
    $('input[code="' + code + '"').val(value);
  })

  // button modal event
  $('[confirm_submit]').click(function(){
    $('#confirm_submit').modal('show');
  });

  $('[confirm_cancel]').click(function(){
    $('#confirm_cancel').modal('show');
  });

  // handle modal
  $('.modal').modal({
    backdrop: 'static',
    show: false
  });

  // register listen change value
  $(document).on('keyup', '[listen]', function(){
    var thisSelector = $(this);
    var value = thisSelector.val();
    var attr = thisSelector.attr('listen');
    $('[listen=' + attr + ']').val(value);
  });

  // Render day list
  $('.day-list-select').each(function(){
    var tmp = [], mIndex = 1;
    var thisSelector = $(this);
    thisSelector.find('option').not('[custom-option]').remove();
    var value = thisSelector.attr('value');
    for(mIndex; mIndex <= 31; mIndex++) {
      tmp.push('<option value="' + mIndex + '">' + mIndex + '</option>');
    }
    thisSelector.append(tmp.join(''));
    if(value) {
      thisSelector.val(value);
    }
  })

  // Render month list
  $('.month-list-select').each(function(){
    var tmp = [], mIndex = 1;
    var thisSelector = $(this);
    thisSelector.find('option').not('[custom-option]').remove();
    var value = thisSelector.attr('value');
    for(mIndex; mIndex <= 12; mIndex++) {
      tmp.push('<option value="' + mIndex + '">' + mIndex + '</option>');
    }
    thisSelector.append(tmp.join(''));
    if(value) {
      thisSelector.val(value);
    }
  })

  // Render year list
  $('.year-list-select').each(function(){
    var currentYear = new Date().getFullYear();
    var thisSelector = $(this);
    thisSelector.find('option').not('[custom-option]').remove();
    var value = thisSelector.attr('value');
    var tmp = [], startYear = +thisSelector.attr('start-year');
    if(!startYear) {
      startYear = currentYear;
    }
    for(currentYear; currentYear >= startYear; currentYear--) {
      tmp.push('<option value="' + currentYear + '">' + currentYear + '</option>');
    }
    thisSelector.append(tmp.join(''));
    if(value) {
      thisSelector.val(value);
    }
  })

  // handle print pdf, add all param to link
  _common.addUrlParamToPDFLink();

  // upload jquery plugin
  $('.uploader').each(function(){
    var thisSelector = $(this);
    $(function () {
      thisSelector.fileupload({
        dropZone: thisSelector.find('.dropzone'),
        add: function (e, data) {
          thisSelector.find('.submit_button').one('click', function (e) {
            $('loading').show();
            e.preventDefault();
            data.submit();
          });
        },
        done: function(e, data) {
          $('loading').hide();
          if(typeof uploaderCallback == 'function') {
            uploaderCallback(data.result);
          }
          thisSelector.find('.dropzone').html('');
        },
        fail: function(e, data) {
          $('loading').hide();
          thisSelector.find('.dropzone').html('');
        }
      }).bind('fileuploadadd', function (e, data) {
        if(data && data['files'] && data['files'][0] && data['files'][0].name) {
          thisSelector.find('.dropzone').html(data['files'][0].name);
        }
      });
    });

    thisSelector.find('.open_button').click(function(){
      thisSelector.find('.open_input').click();
    });
  });

  $('.table-scroll-fake').remove();
  $('.table-scroll').each(function(e){
    var thisSelector = $(this);
    var scrollName = 'scroll-top' + Date.now();
    // add element to table
    $('<div class="table-scroll-fake syncscroll" name="' + scrollName + '"><div class="table-scroll-fake-content"></div></div>').insertBefore(thisSelector);
    $(thisSelector).attr('name', scrollName);
    $(thisSelector).addClass('syncscroll');
    $('[name="' + scrollName + '"]').find('.table-scroll-fake-content').width(thisSelector.find('table').width());
  });

  $('.required-input').keyup(function(){
    _common.checkRequired($(this));
  });

  $('.required-input').change(function(){
    _common.checkRequired($(this));
  });

  // New uploader
  $('.common-upload-button').click(function(){
    var thisSelector = $(this);
    thisSelector.closest('.common-upload').find('.common-upload-input').click();
  });

  $('.common-upload-input').change(function(){
    var thisSelector = $(this);
    var files = thisSelector.prop('files');
    var name = '';
    if(files && files.length) {
      name = files[0].name;
    }
    thisSelector.closest('.common-upload').find('.common-upload-box').val(name);
  });

  $(document).on('change', '.common-kana', function(e){
    var thisSelector = $(this);
    var data = _common.changeFullWidthKana(thisSelector.val());
    thisSelector.val(data);
  });

  $('.ang-content, .ang-scroll').scroll(function () {
    if( document.body.style.cursor == 'wait') {
      return false;
    }
    var thisContent = $(this);
    var childContent = thisContent.find('[ang-load]');
    if(!childContent) {
      return false;
    }
    var childHeight = childContent.outerHeight(true);
    if(thisContent.scrollTop() + thisContent.height() >= childHeight) {
      var loadMode = childContent.attr('ang-load');
      if(loadMode) {
        var ctrl_name = childContent.closest('[selector_id]').attr('selector_id');
        window['$' + ctrl_name][loadMode]();
      }
    }
  })
});
