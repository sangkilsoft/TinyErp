<?php

/**
 * This is the model class for table "tbl_role".
 *
 * The followings are the available columns in table 'tbl_role':
 * @property integer $role_id
 * @property string $role
 * @property string $deskripsi
 *
 * The followings are the available model relations:
 * @property RoleMenu[] $roleMenus
 */
class Role extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @return Role the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_role';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('role', 'required'),
            array('role', 'length', 'max' => 45),
            array('deskripsi', 'length', 'max' => 100),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('role_id, role, deskripsi', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'roleMenus' => array(self::HAS_MANY, 'RoleMenu', 'role_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'role_id' => 'Role',
            'role' => 'Role',
            'deskripsi' => 'Deskripsi Role',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('role_id', $this->role_id);
        $criteria->compare('role', $this->role, true);
        $criteria->compare('deskripsi', $this->deskripsi, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                    'pagination' => array(
                        'pageSize' => 50,
                    ),
                ));
    }

}