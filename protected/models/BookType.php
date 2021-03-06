<?php

/**
 * This is the model class for table "{{_book_type}}".
 *
 * The followings are the available columns in table '{{_book_type}}':
 * @property string $id
 * @property string $title
 * @property integer $parent_id
 */
class BookType extends MyActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return BookType the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{book_type}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title', 'required'),
			array('parent_id', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, title, parent_id', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()	{
		return array(
            'books' => array(self::HAS_MANY, 'Book', 'book_type'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'title' => 'Название',
			'parent_id' => 'Родительская котегория',
		);
	}

    /*
     * Возвращает название родительской категории по id
     */
    public function getParentTypeById($id) {
        $title = $this->model()->findByPk($id)->title;
        return $title;
    }

    /*
     * Возвращает все категории
     */
    public function getAllTypes() {
        return CHtml::listData($this->model()->findAll(), 'id', 'title');
    }

    /*
     * Записываем URL
     */
    protected function beforeSave() {
        if(parent::beforeSave()) {
            if($this->isNewRecord) {
                $this->url = $this->translit($this->title);
            }
            return true;
        } else {
            return false;
        }
    }

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);

		$criteria->compare('title',$this->title,true);

		$criteria->compare('parent_id',$this->parent_id);

		return new CActiveDataProvider('BookType', array(
			'criteria'=>$criteria,
		));
	}
}