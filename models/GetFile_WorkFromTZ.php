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
            [['DbkFile'], 'safe'],
            //[['DbkFile'], 'file', 'skipOnEmpty' => false, 'extensions' => ['dbk','xml']],
        ];
    }
    
    public function upload()
    {
        if ($this->validate()) {
            $this->DbkFile->saveAs('uploads/' . $this->DbkFile->baseName . '.' . $this->DbkFile->extension);
            return true;
        } else {
            return false;
        }
    }
}
?>
