<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<script type="text/javascript" src="static/javascript/product.js"></script>
<script type="text/javascript" src="./data/cachefiles/product_gcategory.js?r=<?=$MOD['jscache_flag']?>"></script>
<div id="body">
<form method="post" action="<?=cpurl($module,$act,'save')?>">
    <div class="space">
        <div class="subtitle">添加/编辑分类</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="45%" class="altbg1"><strong>分类名称：</strong>分类名称，区别产品类别。</td>
                <td width="*"><input type="text" name="t_cat[name]" class="txtbox" value="<?=$category['name']?>" /></td>
            </tr>
            <?if($category['level']>1):?>
            <tr>
                <td class="altbg1"><strong>上级分类:</strong>选择当前分类所属的上级分类</td>
                <td>
                    <?php
                        if($category['level']=='2'){
                            $pid = $category['pid'];
                            $ppid = 0;
                        } elseif($category['level']=='3'){
                            $ppid = $category['pid'];
                            $cat = $_G['loader']->model('product:gcategory')->read($category['pid']);
                            $pid = $cat['pid'];
                        }
                    ?>
                    <select id="product_gcatgory_1" name="t_cat[cate_level1]" onchange="ajax_load_subcategory(this,'<?=$catid?>');">
                        <?=form_product_gcategory(0,$pid,false);?>
                    </select>
                    <?if(($category['modelid'] > 0 && !$category['subcats']) || $category['level'] == '3'):?>
                    <select id="product_gcatgory_2" name="t_cat[cate_level2]">
                        <option value="0"<?if(!$ppid):?>selected="selected"<?endif;?>>=不限=</option>
                        <?=form_product_gcategory($pid,$ppid,$category['modelid'],$catid);?>
                    </select>
                    <?endif;?>
                </td>
            </tr>
            <tr>
                <td class="altbg1">
                    <strong>关联产品模型:</strong>产品添加时，关联模型内的自定义字段；
                    <br /><span class="font_1">不关联模型ID，则无法再本分类添加商品，第三级分类必须关联模型。</span>
                </td>
                <td>
                    <select name="t_cat[modelid]">
                        <option value="0" selected="selected">==选择产品模型==</option>
                        <?=form_product_model($category['modelid']);?>
                    </select>
                </td>
            </tr>
            <?endif;?>
            <tr>
                <td class="altbg1"><strong>页面标题:</strong>打开当前分类列表页时，页面标题名称。</td>
                <td><input type="text" name="t_cat[title]" class="txtbox" value="<?=$category['title']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>页面关键字:</strong>打开当前分类列表页时，页面关键字。</td>
                <td><input type="text" name="t_cat[keywords]" class="txtbox" value="<?=$category['keywords']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>页面描述:</strong>打开当前分类列表页时，页面描述。</td>
                <td><input type="text" name="t_cat[description]" class="txtbox" value="<?=$category['description']?>" /></td>
            </tr>
        </table>
        <center>
            <?if($category):?>
            <input type="hidden" name="catid" value="<?=$category['catid']?>">
            <?else:?>
            <input type="hidden" name="t_cat[pid]" value="<?=$pid?>">
            <?endif;?>
            <input type="hidden" name="do" value="<?=$op?>">
            <input type="hidden" name="forward" value="<?=get_forward()?>" />
            <button type="submit" name="dosubmit" value="yes" class="btn"> 提 交 </button>&nbsp;
            <button type="button" class="btn" onclick="history.go(-1);"> 返 回 </button>
        </center>
    </div>
</form>
</div>
<script type="text/javascript">
function ajax_load_subcategory (myobj,catid) {
    var myval = $(myobj).val();
    if(!myval) return;
    var subobj = $('#product_gcatgory_2');
    if(!subobj[0]) return;
    $.post('<?=cpurl($module,$act,'load_subcategory')?>', {'pid':myval, 'catid':catid, 'in_ajax':1 }, 
        function(result) {
        if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
        } else if(is_json(result)) {
            subobj.empty();
            subobj.append($('<option value="0">=不限=</option>'))
            var mymsg = eval('('+result+')'); //JSON转换
            for(x in mymsg) {
                subobj.append($('<option value="'+x+'">'+mymsg[x]+'</option>'))
            }
        } else {
            //alert(result);
            alert('读取失败，可能网络忙碌，请稍后尝试。');
        }
    });
}

function js_unserialize (data) {
  // http://kevin.vanzonneveld.net
  // +     original by: Arpad Ray (mailto:arpad@php.net)
  // +     improved by: Pedro Tainha (http://www.pedrotainha.com)
  // +     bugfixed by: dptr1988
  // +      revised by: d3x
  // +     improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +        input by: Brett Zamir (http://brett-zamir.me)
  // +     improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +     improved by: Chris
  // +     improved by: James
  // +        input by: Martin (http://www.erlenwiese.de/)
  // +     bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +     improved by: Le Torbi
  // +     input by: kilops
  // +     bugfixed by: Brett Zamir (http://brett-zamir.me)
  // +      input by: Jaroslaw Czarniak
  // %            note: We feel the main purpose of this function should be to ease the transport of data between php & js
  // %            note: Aiming for PHP-compatibility, we have to translate objects to arrays
  // *       example 1: unserialize('a:3:{i:0;s:5:"Kevin";i:1;s:3:"van";i:2;s:9:"Zonneveld";}');
  // *       returns 1: ['Kevin', 'van', 'Zonneveld']
  // *       example 2: unserialize('a:3:{s:9:"firstName";s:5:"Kevin";s:7:"midName";s:3:"van";s:7:"surName";s:9:"Zonneveld";}');
  // *       returns 2: {firstName: 'Kevin', midName: 'van', surName: 'Zonneveld'}
  var that = this,
    utf8Overhead = function (chr) {
      // http://phpjs.org/functions/unserialize:571#comment_95906
      var code = chr.charCodeAt(0);
      if (code < 0x0080) {
        return 0;
      }
      if (code < 0x0800) {
        return 1;
      }
      return 2;
    },
    error = function (type, msg, filename, line) {
      throw new that.window[type](msg, filename, line);
    },
    read_until = function (data, offset, stopchr) {
      var i = 2, buf = [], chr = data.slice(offset, offset + 1);

      while (chr != stopchr) {
        if ((i + offset) > data.length) {
          error('Error', 'Invalid');
        }
        buf.push(chr);
        chr = data.slice(offset + (i - 1), offset + i);
        i += 1;
      }
      return [buf.length, buf.join('')];
    },
    read_chrs = function (data, offset, length) {
      var i, chr, buf;

      buf = [];
      for (i = 0; i < length; i++) {
        chr = data.slice(offset + (i - 1), offset + i);
        buf.push(chr);
        length -= utf8Overhead(chr);
      }
      return [buf.length, buf.join('')];
    },
    _unserialize = function (data, offset) {
      var dtype, dataoffset, keyandchrs, keys,
        readdata, readData, ccount, stringlength,
        i, key, kprops, kchrs, vprops, vchrs, value,
        chrs = 0,
        typeconvert = function (x) {
          return x;
        };

      if (!offset) {
        offset = 0;
      }
      dtype = (data.slice(offset, offset + 1)).toLowerCase();

      dataoffset = offset + 2;

      switch (dtype) {
        case 'i':
          typeconvert = function (x) {
            return parseInt(x, 10);
          };
          readData = read_until(data, dataoffset, ';');
          chrs = readData[0];
          readdata = readData[1];
          dataoffset += chrs + 1;
          break;
        case 'b':
          typeconvert = function (x) {
            return parseInt(x, 10) !== 0;
          };
          readData = read_until(data, dataoffset, ';');
          chrs = readData[0];
          readdata = readData[1];
          dataoffset += chrs + 1;
          break;
        case 'd':
          typeconvert = function (x) {
            return parseFloat(x);
          };
          readData = read_until(data, dataoffset, ';');
          chrs = readData[0];
          readdata = readData[1];
          dataoffset += chrs + 1;
          break;
        case 'n':
          readdata = null;
          break;
        case 's':
          ccount = read_until(data, dataoffset, ':');
          chrs = ccount[0];
          stringlength = ccount[1];
          dataoffset += chrs + 2;

          readData = read_chrs(data, dataoffset + 1, parseInt(stringlength, 10));
          chrs = readData[0];
          readdata = readData[1];
          dataoffset += chrs + 2;
          if (chrs != parseInt(stringlength, 10) && chrs != readdata.length) {
            error('SyntaxError', 'String length mismatch');
          }
          break;
        case 'a':
          readdata = {};

          keyandchrs = read_until(data, dataoffset, ':');
          chrs = keyandchrs[0];
          keys = keyandchrs[1];
          dataoffset += chrs + 2;

          for (i = 0; i < parseInt(keys, 10); i++) {
            kprops = _unserialize(data, dataoffset);
            kchrs = kprops[1];
            key = kprops[2];
            dataoffset += kchrs;

            vprops = _unserialize(data, dataoffset);
            vchrs = vprops[1];
            value = vprops[2];
            dataoffset += vchrs;

            readdata[key] = value;
          }

          dataoffset += 1;
          break;
        default:
          error('SyntaxError', 'Unknown / Unhandled data type(s): ' + dtype);
          break;
      }
      return [dtype, dataoffset - offset, typeconvert(readdata)];
    }
  ;

  return _unserialize((data + ''), 0)[2];
}
</script>