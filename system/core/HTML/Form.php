<?
namespace system\core\HTML;

use Exception;

class Field
{
    public $error = '';
    public $description = '';
    //public $required = false;
    public $htmlBefore = '';
    public $htmlAfter = '';
    public $htmlBeforeField = '';
    public $htmlAfterField = '';
    public $custom_check = null;
    public $label = '';
    protected $properties = array();

    public function __construct($args = null)
    {
        if (is_array($args)) {
            foreach ($args as $key => $val)
            {
                $this->$key = $val;
            }
        }
    }
    public function get($name)
    {
        return isset($this->properties[$name]) ? $this->properties[$name] : null;
    }
    public function __get($name)
    {
        return $this->get($name);
    }
    public function set($name, $value)
    {
        $this->properties[$name] = $value;
        return $this;
    }
    public function __set($name, $value)
    {
        $this->set($name, $value);
    }
    public function __isset($name)
    {
        return array_key_exists($name, $this->properties);
    }
    protected function getAttributeHtml($name)
    {
        return !is_null($this->$name) ? $name . '="' . $this->$name . '" ' : '';
    }

    protected function getAttributesHtml($exclude = null)
    {
        $out = '';
        $exclude = explode(',', trim($exclude));
        foreach ($this->properties as $name => $value)
        {
            !in_array($name, $exclude) && $out .= $this->getAttributeHtml($name);
        }
        return $out;
    }
    public function check()
    {
        if (is_callable($this->custom_check) && !$this->error) {        //пользователься функция проверки
            $this->error = call_user_func($this->custom_check, $this);
        }
    }
    public function submit()
    {
        $this->disabled || $this->value = @trim($_POST[$this->name]);
        if ($this->values && !array_key_exists($this->value, $this->values)) throw new Exception("Выбран недопустимый вариант <pre>" . print_r($this, 1) . "</pre>");
    }
    public function html()
    {
    }
    public function setProperty($name, $value)
    {
        $this->properties[$name] = $value;
        return $this;
    }
    public function getProperty($name)
    {
        return array_key_exists($name, $this->properties) ? $this->properties[$name] : null;
    }
}

class Input extends Field
{
    public function html()
    {
        $html = '<input ' . $this->getAttributesHtml() . '/>';
        return $html;
    }
}

class InputText extends Input
{
    public $regex = '';
    public function check()
    {
        $this->error = '';
        if (!strlen($this->value) && $this->required) {
            $this->error = 'не заполнено обязательное поле';
        } elseif (strlen($this->value) && $this->regex && !preg_match($this->regex, $this->value)) {
            $this->error = 'неправильно заполнено поле'/* . $this->re*/;
        }
        parent::check();
        return $this->error;
    }
}
class InputNumber extends InputText
{
    public function check()
    {
        if (!parent::check()) {
            if (strlen($this->value) && !is_numeric($this->value)) {
                $this->error = 'поле может содержать только числа'/* . $this->re*/;
            } elseif ($this->value && $this->min && $this->value < $this->min) {
                $this->error = "минимальное значение $this->min"/* . $this->re*/;
            }
        };
        return $this->error;
    }
}
class InputPassword extends InputText
{

}

class InputHidden extends InputText
{

}
class InputEmail extends InputText
{
    public $regex = '/[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$/';
}
class InputUrl extends InputText
{
    //public $pattern = '/[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$/';
}
class InputCheckbox extends Input
{
    public function submit()
    {
        if (!$this->disabled && strlen(@$_POST[$this->name])) {
            $this->value = array_key_exists($this->name, $_POST);
            $this->checked = 'checked';
        }  elseif (!$this->disabled) { //todo переделать
            $this->value = null;
        }
    }
    public function html($full = false)
    {
        $this->value && $this->checked = 'checked';
        $html = '<input ' . $this->getAttributesHtml('value') . '/>';
        $full && $html = $this->template($html);      
        return $html;
    }
    protected function template($input_html)
    {
        $label = $this->label ? "" : '';
        $description = $this->description ? "<p class=\"help-block\">$this->description</p>" : '';
        $error = $this->error ? "<span class=\"field-error\">$this->error</span>" : '';
        $html = $this->htmlBefore;
        $html .= '<div class="checkbox">';
        $html .= "<label>$input_html $this->label</label>";
        $html .= $description;
        $html .= $error;
        $html .= '</div>';
        $html .= $this->htmlAfter;
        return $html;
    }
}

class InputCheckboxList extends Input
{
    public $values = array();

    public function submit()
    {
        if (@array_diff($_POST[$this->name], array_keys($this->values))) throw new Exception('Недопустимые данные');
        $this->value = @count($_POST[$this->name]) ? $_POST[$this->name] : array();
    }
    public function html()
    {
        $html = '';
        foreach ($this->values as $value => $title)
        {
            $checked = @in_array($value, $this->value) ? ' checked="checked"' : '';
            $html .= "<label class=\"checkbox\"><input type=\"checkbox\" value=\"$value\" name=\"{$this->name}[]\"$checked /> <span>$title</span></label>";
        }
        return $html;
    }
    public function check()
    {
        $this->error = '';
        if (!$this->value && $this->required) {
            $this->error = 'выбор обязателен';
        }
        return $this->error;
    }
}

class InputSubmit extends Input
{
}

class InputButton extends Input
{
}

class InputFile extends Input
{
    public $upload_dir = __DIR__;
    public $uploaded_file = false;
    public $upload = false;
    public $allowed_ext = array();

    public function submit()
    {
        $this->disabled || $this->value = @$_FILES[$this->name]['name'];
    }
    public function check()
    {
        $this->error = null;
        if (!strlen($this->value) && $this->required) {
            $this->error = 'Не выбран файл для загрузки';
        }
        if ($this->value && $_FILES[$this->name]['error']) {
            $errors = array(
                0 => 'файл успешно загружен',
                1 => 'Размер принятого файла превысил максимально допустимый размер',
                2 => 'Размер выбранного файла превышает допустимое значение',
                3 => 'Загружаемый файл был получен только частично',
                4 => 'Файл не выбран',
                6 => 'Отсутствует временная папка',
                7 => 'Не удалось записать файл на диск',
                8 => 'PHP-расширение остановило загрузку файла'
            );
            $this->error = isset($errors[$_FILES[$this->name]['error']]) ? $errors[$_FILES[$this->name]['error']] : 'Неизвестная ошибка';
        }
        if ($this->allowed_ext && $this->value && !$this->error) {
            $ext = strtolower(pathinfo($this->value, PATHINFO_EXTENSION));
            if (array_search($ext, $this->allowed_ext) === false) $this->error = 'Недопустимый тип файла';
        }
        if (strlen($this->value) && $this->upload && !$this->error) {
            $this->uploaded_file = $this->uploadFile();
        }
        return $this->error;
    }
    public function uploadFile()
    {
        if (@$_FILES[$this->name]['error'] || !$this->value) return false;
        else {
            $path = rtrim($this->upload_dir, '/') . '/' . $this->value;
            $path_info = pathinfo($path);
            $path_info['filename'] = strrpos($this->value, '.') ? substr($this->value, 0, strrpos($this->value, '.')) : $this->value;
            while(file_exists($path)) {
                static $i = 0;
                $i++;
                $path = $path_info['dirname'] . DIRECTORY_SEPARATOR . $path_info['filename'] . "($i)." . $path_info['extension'];

            }
            return move_uploaded_file(@$_FILES[$this->name]['tmp_name'], $path) ? $path_info['filename'] . (isset($i) ? "($i)" : "") . "." . $path_info['extension'] : false;
        }
    }
}

class InputRadio extends Input
{
    public $values = array();
    public $label_class = 'radio inline';
    public function html()
    {
        $html = '';
        //throw new Exception(print_r($this->values, 1));
        foreach ($this->values as $value => $title)
        {
            $this->checked = $this->value == $value ? 'checked' : null;
            $html .= '<label class="' . $this->label_class . '"><input ' . $this->getAttributesHtml('value') . 'value="' . $value . '" />' . $title . ' </label>';
        }
        return $html;
    }
}

class Select extends Field
{
    public $values = array();
    public $option_attributes = array();
    public function submit()
    {
        if ($this->multiple) {
            if (@array_diff($_POST[$this->name], array_keys($this->values))) throw new Exception('Недопустимые данные');
            $this->value = @count($_POST[$this->name]) ? $_POST[$this->name] : array();
        } else {
            parent::submit();
        }
    }
    public function html()
    {
        $html = '';
        foreach ($this->values as $value => $text)
        {
            $selected = "$this->value" == "$value" ? 'selected="selected" ' : null;
            $option_attr_html = '';
            if (array_key_exists($value, $this->option_attributes) && is_array($this->option_attributes[$value])) {
                foreach ($this->option_attributes[$value] as $attr_name => $attr_value) {
                    $option_attr_html .= "$attr_name=\"$attr_value\" ";
                }
            }
            //if ($this->name=='manager_id' && $value !='') {var_dump($option_attr_html); die();}
            $html .= "<option $option_attr_html $selected value=\"$value\">$text</option>";
        }

        $html = '<select ' . $this->getAttributesHtml('field') . '>' . $html . '</select>';
        return $html;
    }
    public function check()
    {
        $this->error = null;
        !strlen($this->value) && $this->required && $this->error = 'Выберите вариант из списка';
        return $this->error;
    }

}

class TextArea extends Field
{
    public $regex = '';
    public function html()
    {
        $html = '<textarea ' . $this->getAttributesHtml() . '>' . $this->value . '</textarea>';
        return $html;
    }
    public function check()
    {
        $this->error = null;
        if (!strlen($this->value) && $this->required) {
            $this->error = 'не заполнено обязательное поле';
        } elseif (strlen($this->value) && $this->regex && !preg_match($this->regex, $this->value)) {
            $this->error = 'неправильно заполнено поле';
        }
        return $this->error;
    }
}

class Form implements \Iterator
{
    public $errors = array();
    public $submitted = false;
    private $default_field = 'input';
    private $default_type = 'text';
    private $method = 'post';
    private $action = '#';
    private $class = 'form';
    private $enctype = "application/x-www-form-urlencoded";
    private $fields = array();
    private $before_submit = null; //функция будет вызвана перед проверкой формы

    /**
     * @param $name
     * @return Field|null
     */
    public function getField($name)
    {
        return isset($this->fields[$name]) ? $this->fields[$name] : null;
    }
    public function field($name)
    {
        return $this->getField($name);
    }
    public function __construct($data)
    {
        if (is_array($data) && is_array($data['fields'])) {
            foreach ($data['fields'] as $field) {
                $class_name =  $this->getFieldClassName(@$field['field'] , @$field['type']);
                if (!@is_subclass_of($class_name, __NAMESPACE__ . '\\Field')) throw new Exception ('Неверный тип поля формы: ' . $class_name);
                if (empty($field['name'])) throw new Exception ('Не указано имя поля формы');
                $this->fields[$field['name']] = new $class_name($field);
            }
            isset($data['class'])   && $this->class = $data['class'];
            isset($data['enctype']) && $this->enctype = $data['enctype'];
            isset($data['before_submit']) && $this->before_submit = $data['before_submit'];
            $this->action = isset($data['action']) ? $data['action'] : $_SERVER['REQUEST_URI'];
        } else throw new Exception ('Ошибка в данных для создания формы');
    }
    private function getFieldClassName($field = '', $type = '')
    {
        $class_name =  __NAMESPACE__ . '\\';
        if ($field == 'select' || $field == 'textarea') {
            $class_name .= $field;
        } else {
            $class_name .= $field ?: $this->default_field;
            $class_name .= $type ?: $this->default_type;
        }
        return $class_name;
    }
    public static function jsonLoad($file)
    {
        $data = @file_get_contents($file);
        if ($data == false) throw new Exception("Не удалось прочитать файл <b>$file</b>");
        $data = json_decode($data, 1);
        if ($data == false) throw new Exception("Неверный формат файла <b>$file</b>");
        return $data;
    }
    public function submit($submit = true)
    {
        if ($submit) {
            if (is_callable($this->before_submit)) call_user_func($this->before_submit, $this);
            foreach ($this->fields as $field) {
                if ($field->disabled) continue;
                array_key_exists($field->name, $_POST) ||
                array_key_exists($field->name, $_FILES) ||
                $field instanceof InputCheckbox ||
                $field instanceof InputCheckboxList ? $field->submit() : null;
                $error = $field->check();
                $error ? $this->errors[$field->name] =  $error : null;
            }
            $this->submitted = true;
        }
    }
    public function html()
    {
        $html = "<form method=\"$this->method\" action=\"$this->action\" class=\"$this->class\" enctype=\"$this->enctype\">";
        $html .= isset($this->errors['form']) ? '<p class="alert alert-danger">' . $this->errors['form'] . '</p>' : '';
        foreach ($this->fields as $field) {
            if ($field instanceof InputCheckbox) {
                $html .= $field->html(true);
            } else {
                $label = $field->label ? "<label>$field->label</label>" : '';
                $description = $field->description ? "<p class=\"help-block\">$field->description</p>" : '';
                $error = $field->error ? "<span class=\"field-error\">$field->error</span>" : '';
                $html .= $field->htmlBefore;
                $html .= '<div class="form-group">';
                $html .= $label;
                $html .= $field->htmlBeforeField;
                $html .= $field->html();
                $html .= $field->htmlAfterField;
                $html .= $description;
                $html .= $error;
                $html .= '</div>';
                $html .= $field->htmlAfter;
            }
        }
        $html .= '</form>';
        return $html;
    }
    public function getValues()
    {
        $values = array();
        foreach ($this->fields as $field) {
            $values[$field->name] = $field->value;
        }
        return $values;
    }
    public function setValues($values)
    {
        if (is_object($values)) {
            $values = get_object_vars($values);
        }
        foreach ($this->fields as $field)
        {
            isset($values[$field->name]) && $field->value = $values[$field->name];
        }
    }
    public function __set($name, $value)
    {
        $this->fields->$name = $value;
    }
    public function __get($name)
    {
        if (!array_key_exists($name, $this->fields)) return null;
        return $this->fields[$name];
    }
    public function getFields()
    {
        return $this->fields;
    }
    public function removeField($name)
    {
        unset($this->fields[$name]);
        return $this;
    }
    public function fieldExists($name)
    {
        return array_key_exists($name, $this->fields);
    }
    function rewind() {
        reset($this->fields);
    }
    function current() {
        return current($this->fields);
    }

    function key() {
        return key($this->fields);
    }

    function next() {
        next($this->fields);
    }

    function valid() {
        return !is_null(key($this->fields));
    }
}
