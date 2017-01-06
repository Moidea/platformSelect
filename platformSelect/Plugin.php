<?php
/**
 * 文章适用平台（自定义字段）选择插件。
 * 
 * @package platformSelect
 * @author 枫叶
 * @version 1.0.0
 * @link https://www.qiansw.com
 */

class platformSelect_Plugin implements Typecho_Plugin_Interface {

    public static function activate() {
    	Typecho_Plugin::factory('admin/write-post.php')->option = array('platformSelect_Plugin', 'render');
    }

    public static function deactivate(){}

    public static function config(Typecho_Widget_Helper_Form $form) {

	    /** 配置欢迎话语 */
	    $name = new Typecho_Widget_Helper_Form_Element_Text('sectionName', NULL, '平台选择', _t('选择器名称'));
	    $os = new Typecho_Widget_Helper_Form_Element_Text('sectionOS', NULL, 'Windows|Linux|macOS|iOS|Android', _t('系统类型（以 | 分隔，仅允许英文，开头和结尾不能出现 |）'));
        $attention = new Typecho_Widget_Helper_Form_Element_Text('attention', NULL, '这里不用填写，看上方注意事项。', _t('<span style="color:red">注意：选择框只能在第一次写文章时使用，修改文章时平台名称会变回自定义字段。</span>'));
	    $form->addInput($name);
	    $form->addInput($os);
        $form->addInput($attention);
    }

    public static function personalConfig(Typecho_Widget_Helper_Form $form){}

    public function render(){

    	$list = "";
        $script = "";

    	$osList = Typecho_Widget::widget('Widget_Options')->plugin('platformSelect')->sectionOS;
    	$arrOsList = explode("|",$osList);

    	for ($x=0; $x<count($arrOsList); $x++) {
			$list = $list.sprintf('<li><input type="checkbox" id="os-%s" class="os">
                                <label>%s</label></li>
                                ',$arrOsList[$x],$arrOsList[$x],$arrOsList[$x],$arrOsList[$x]);
            $script = $script.sprintf('
            $("#os-%s").click(function(){

                if ($("#os-%s").is(\':checked\')){
                    $("#custom-field").append("<p id=\'p-%s\' style=\'display:none\'><input type=\'text\' name=\'fieldNames[]\' value=\'%s\' /><input type=\'text\' name=\'fieldTypes[]\' value=\'int\' /><input type=\'text\' name=\'fieldValues[]\' value=\'1\' /></p>");
                } else {
                    $("#p-%s").remove();
                }
                
            })
',$arrOsList[$x],$arrOsList[$x],$arrOsList[$x],$arrOsList[$x],$arrOsList[$x]);
		} 

    	$html = sprintf('
			<section class="typecho-post-option category-option">
			<label class="typecho-label">%s </label><div style="color:red;margin-top:10px;font-size:.8rem;">仅写文章时可用，修改时请删除左侧自定义字段，增加时可以继续点击。</div>
				<ul>
				%s
				</ul>
			</section>
            <script src="http://t.qiansw.com/admin/js/jquery.js?v=14.10.10"></script>
			<script>
                %s
			</script>
			',
			Typecho_Widget::widget('Widget_Options')->plugin('platformSelect')->sectionName,
			$list,$script
			);
    	echo $html;
	}
}
?>

