<?php
namespace app\models;

use yii\base\Model;
use yii\web\UploadedFile;

class GetFile_WorkFromTZ extends Model
{
    /**
     * @var UploadedFile
     */
    public $DbkFile;

    public function rules()
    {
        return [
            //[['DbkFile'], 'required'],
            //[['DbkFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'dbk'],
            [['DbkFile'], 'file', 'extensions' => 'dbk',  'skipOnEmpty' => false,'checkExtensionByMimeType' => false]
        ];
    }
    
    public function upload()
    {
        if ($this->validate()) {
			//echo '-'.$this->DbkFile->extension.'-'; die;
            $this->DbkFile->saveAs('uploads/' . $this->DbkFile->baseName . '.' . $this->DbkFile->extension);
            return true;
        } else {
            return false;
        }
    }
}
?>
