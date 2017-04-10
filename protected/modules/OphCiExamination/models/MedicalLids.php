<?php
/**
 * OpenEyes
 *
 * (C) OpenEyes Foundation, 2016
 * This file is part of OpenEyes.
 * OpenEyes is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 * OpenEyes is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License along with OpenEyes in a file titled COPYING. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package OpenEyes
 * @link http://www.openeyes.org.uk
 * @author OpenEyes <info@openeyes.org.uk>
 * @copyright Copyright (c) 2016, OpenEyes Foundation
 * @license http://www.gnu.org/licenses/gpl-3.0.html The GNU General Public License V3.0
 */

namespace OEModule\OphCiExamination\models;


/**
 * Class MedicalLids
 * @package OEModule\OphCiExamination\models
 * @property int $id
 * @property int $event_id
 * @property int $eye_id
 * @property string $left_eyedraw
 * @property string $left_ed_report
 * @property string $left_comments
 * @property boolean $left_stfb
 * @property string $right_eyedraw
 * @property string $right_ed_report
 * @property string $right_comments
 * @property boolean $right_stfb
 *
 * The followings are the available model relations:
 * @property \EventType $eventType
 * @property \Event $event
 * @property \Eye $eye
 * @property \User $user
 * @property \User $usermodified
 */
class MedicalLids extends \SplitEventTypeElement
{
    /**
     * Returns the static model of the specified AR class.
     *
     * @return the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'et_ophciexamination_medical_lids';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('eye_id, left_eyedraw, left_ed_report, left_comments, left_stfb, ' .
                'right_eyedraw, right_ed_report, right_comments, right_stfb', 'safe'),
            array('left_eyedraw', 'requiredIfSide', 'side' => 'left'),
            array('right_eyedraw', 'requiredIfSide', 'side' => 'right'),
            array('left_ed_report', 'requiredIfSide', 'side' => 'left'),
            array('right_ed_report', 'requiredIfSide', 'side' => 'right'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, event_id, eye_id, left_eyedraw, left_ed_report, left_comments, left_stfb, ' .
                'right_eyedraw, right_ed_report, right_comments, right_stfb', 'safe', 'on' => 'search'),
        );
    }

    public function sidedFields()
    {
        return array('ed_report', 'comments', 'eyedraw', 'stfb');
    }

    public function canCopy()
    {
        return true;
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'eventType' => array(self::BELONGS_TO, 'EventType', 'event_type_id'),
            'event' => array(self::BELONGS_TO, 'Event', 'event_id'),
            'eye' => array(self::BELONGS_TO, 'Eye', 'eye_id'),
            'user' => array(self::BELONGS_TO, 'User', 'created_user_id'),
            'usermodified' => array(self::BELONGS_TO, 'User', 'last_modified_user_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'event_id' => 'Event',
            'left_eyedraw' => 'Eyedraw',
            'left_ed_report' => 'Report',
            'left_comments' => 'Comments',
            'left_stfb' => 'STFB',
            'right_eyedraw' => 'Eyedraw',
            'right_comments' => 'Comments',
            'right_ed_report' => 'Report',
            'right_stfb' => 'STFB'
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new \CDbCriteria();

        $criteria->compare('id', $this->id, true);
        $criteria->compare('event_id', $this->event_id, true);

        $criteria->compare('left_eyedraw', $this->left_eyedraw);
        $criteria->compare('left_ed_report', $this->left_ed_report);
        $criteria->compare('left_comments', $this->left_comments);
        $criteria->compare('left_stfb', $this->left_stfb);
        $criteria->compare('right_eyedraw', $this->right_eyedraw);
        $criteria->compare('right_ed_report', $this->right_ed_report);
        $criteria->compare('right_comments', $this->right_comments);
        $criteria->compare('right_stfb', $this->right_stfb);

        return new \CActiveDataProvider(get_class($this), array(
            'criteria' => $criteria,
        ));
    }
}