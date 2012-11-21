<?php
/**
 * OpenEyes
 *
 * (C) Moorfields Eye Hospital NHS Foundation Trust, 2008-2011
 * (C) OpenEyes Foundation, 2011-2012
 * This file is part of OpenEyes.
 * OpenEyes is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 * OpenEyes is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License along with OpenEyes in a file titled COPYING. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package OpenEyes
 * @link http://www.openeyes.org.uk
 * @author OpenEyes <info@openeyes.org.uk>
 * @copyright Copyright (c) 2008-2011, Moorfields Eye Hospital NHS Foundation Trust
 * @copyright Copyright (c) 2011-2012, OpenEyes Foundation
 * @license http://www.gnu.org/licenses/gpl-3.0.html The GNU General Public License V3.0
 */

/**
 * This is the model class for table "patient_oph_info" It is designed to store ophthamological specific information against a patient, and is a model for any other specialty specific information (hence named 
 * after the specialty code).
 *
 * The followings are the available columns in table 'patient_oph_info':
 * @property integer	$id
 * @property integer  $patient_id
 * @property string  $cvi_status_date
 * @property integer  $cvi_status_id
 * @property datetime  $created_date
 * @property datetime  $last_modified_date
 * @property integer	$created_user_id
 * @property integer	$last_modified_user_id
 *
 * The followings are the available model relations:
 * @property Patient $patient
 * @property PatientOphInfoCviStatus $cvi_status
 */
 
class PatientOphInfo extends BaseActiveRecord
{

	/**
	 * Returns the static model of the specified AR class.
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
		return 'patient_oph_info';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
				array('cvi_status_date, cvi_status_id', 'safe'),
				array('cvi_status_date, cvi_status_id', 'required'),
				array('cvi_status_id', 'safe', 'on' => 'search'),
				array('cvi_status_date', 'OeFuzzyDateValidator'),
		);
	}


	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
				'cvi_status' => array(self::BELONGS_TO, 'PatientOphInfoCviStatus', 'cvi_status_id'),
				'patient' => array(self::BELONGS_TO, "Patient", 'patient_id'),
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
				'patient_id' => 'Patient',
				'cvi_status_id' => 'CVI Status',
				'cvi_status_date' => 'CVI Status Date',
		);
	}
	
	// TODO: Finish the model, and then get the form into the patient summary.

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id, true);
		$criteria->compare('patient_id', $this->patient_id, true);

		return new CActiveDataProvider(get_class($this), array(
				'criteria' => $criteria,
		));
	}
	
}