<?php
namespace Upg\Library\Request;

/**
 * Class GetUser
 * The getUserData call adds the functionality to get the data and the status of a user.
 * Future functionality of this call will include delivery of risk level. Input data consist of the following:
 * User information (existing user-id or complete user data)
 * @link https://documentation.upgplc.com/hostedpagesdraft/en/topic/getuserstatus
 * @package Upg\Library\Request
 */
class GetUser extends AbstractRequest
{
    /**
     * The unique user id of the customer.
     * @var string
     */
    private $userID;

    /**
     * Set the userID field
     * @see GetUser::userID
     * @param $userID
     * @return $this
     */
    public function setUserID($userID)
    {
        $this->userID = $userID;
        return $this;
    }

    /**
     * @see GetUser::userID
     * @return string
     */
    public function getUserID()
    {
        return $this->userID;
    }

    public function getPreSerializerData()
    {
        return array(
            'userID' => $this->getUserId(),
        );
    }

    /**
     * Validation meta data
     * @return array
     */
    public function getClassValidationData()
    {
        $validationData = array();

        $validationData['userID'][] = array(
            'name' => 'required',
            'value' => null,
            'message' => "userID is required"
        );

        $validationData['userID'][] = array(
            'name' => 'MaxLength',
            'value' => '50',
            'message' => "userID must be between 1 and 50 characters"
        );

        return $validationData;
    }
}
