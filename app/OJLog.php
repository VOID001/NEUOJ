<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class OJLog extends Model
{

    /*
     * @function writeLogger
     * @input $context, $level
     *
     * @description
     */
    public static function writeLogger($context, $level)
    {
        $logger = new Logger('Online Judge');
        $logFormat = "[%datetime%] %channel%.%level_name%: %message% \n";
        $formatter = new LineFormatter($logFormat);
        $stream = new StreamHandler('/var/log/neuoj/neuoj.log');
        $stream->setFormatter($formatter);
        $logger->pushHandler($stream);
        $logger->addRecord($level, $context);
    }

    /*
     * @function loginInfo
     * @input $uid, $ip
     *
     * @description splice login info
     */
    public static function loginInfo($uid, $ip)
    {
        $context = "User " . $uid . " logged in NEUOJ at " . $ip . ".";
        self::writeLogger($context, Logger::INFO);
    }

    /*
     * @function logoutInfo
     * @input $uid, $ip
     *
     * @description splice logout info
     */
    public static function logoutInfo($uid, $ip)
    {
        $context = "User " . $uid . " logged out NEUOJ at " . $ip . ".";
        self::writeLogger($context, Logger::INFO);
    }

    /*
     * @function changePassword
     * @input $uid
     *
     * @description splice change password info
     */
    public static function changePassword($uid)
    {
        $context = "User " . $uid . " changed password. ";
        self::writeLogger($context, Logger::NOTICE);
    }

    /*
     * @function changeProfile
     * @input $uid, $oldProfile, $newProfile
     *
     * @description splice change profile info
     */
    public static function changeProfile($uid, $oldProfile, $newProfile)
    {
        $context = "User " . $uid . " changed profile from " . $oldProfile . " to " . $newProfile;
        self::writeLogger($context, Logger::INFO);
    }

    /*
     * @function deleteDiscuss
     * @input $uid, $contestId, $problemId, $deleteContent
     *
     * @description splice delete discuss info
     */
    public static function deleteDiscuss($uid, $contestId, $problemId, $deleteContent)
    {
        $context = "Admin " . $uid . " deleted a discuss of Contest " . $contestId . " Problem " . $problemId . ".Its content:" . $deleteContent;
        self::writeLogger($context, Logger::NOTICE);
    }

    /*
     * @function addContest
     * @input $uid, $contestId
     *
     * @description splice add contest info
     */
    public static function addContest($uid, $contestId)
    {
        $context = "Admin " . $uid . " added Contest " . $contestId . ".";
        self::writeLogger($context, Logger::NOTICE);
    }

    /*
     * @function editContest
     * @input $uid, $contestId, $oldContent, $newContent
     *
     * @description splice edit contest info
     */
    public static function editContest($uid, $contestId, $oldContent, $newContent)
    {
        $context = "Admin " . $uid . " edited Contest " . $contestId . " from " . $oldContent . " to " . $newContent;
        self::writeLogger($context, Logger::ALERT);
    }

    /*
     * @function deleteContest
     * @input $uid, $contestId, $deleteContent
     *
     * @description splice delete contest info
     */
    public static function deleteContest($uid, $contestId, $deleteContent)
    {
        $context = "Admin " . $uid . " deleted Contest " . $contestId . ".Its content:" . $deleteContent;
        self::writeLogger($context, Logger::ALERT);
    }


    /*
     * @function addProblem
     * @input $uid, $problemId
     *
     * @description splice add problem info
     */
    public static function addProblem($uid, $problemId)
    {
        $context = "Admin " . $uid . " added Problem " . $problemId . ".";
        self::writeLogger($context, Logger::NOTICE);
    }

    /*
     * @function editProblem
     * @input $uid, $problemId, $oldContent, $newContent
     *
     * @description splice edit problme info
     */
    public static function editProblem($uid, $problemId, $oldContent, $newContent)
    {
        $context = "Admin " . $uid . " edited Problem " . $problemId . " from " . $oldContent . " to " . $newContent;
        self::writeLogger($context, Logger::ALERT);
    }

    /*
     * @function deleteProblem
     * @input $uid, $problemId, $deleteContent
     *
     * @description splice delete problem info
     */
    public static function deleteProblem($uid, $problemId, $deleteContent)
    {
        $context = "Admin " . $uid . " deleted Problem " . $problemId . ".Its content:" . $deleteContent;
        self::writeLogger($context, Logger::ALERT);
    }

    /*
     * @function addTrain
     * @input $uid, $trainId
     *
     * @description splice add train info
     */
    public static function addTrain($uid, $trainId)
    {
        $context = "Admin " . $uid . " added Train " . $trainId . ".";
        self::writeLogger($context, Logger::NOTICE);
    }

    /*
     * @function editTrain
     * @input $uid, $trainId, $oldContent, $newContent
     *
     * @description splice edit train info
     */
    public static function editTrain($uid, $trainId, $oldContent, $newContent)
    {
        $context = "Admin " . $uid . " edited Train " . $trainId . " from " . $oldContent . " to " . $newContent;
        self::writeLogger($context, Logger::ALERT);
    }

    /*
     * @function deleteTrain
     * @input $uid, $trainId, $deleteContent
     *
     * @description splice delete train info
     */
    public static function deleteTrain($uid, $trainId, $deleteContent)
    {
        $context = "Admin " . $uid . " deleted Train " . $trainId . ".Its content:" . $deleteContent;
        self::writeLogger($context, Logger::ALERT);
    }

    /*
     * @function setTeacher
     * @input $gid, $uid
     *
     * @description splice set teacher info
     */
    public static function setTeacher($aid, $uid)
    {
        $context = "Admin " . $aid . " set User " . $uid . " as Teacher.";
        self::writeLogger($context, Logger::ALERT);
    }

    /*
     * @function setAdmin
     * @input $gid, $uid
     *
     * @description splice set admin info
     */
    public static function setAdmin($aid, $uid)
    {
        $context = "Admin " . $aid . " set User " . $uid . " as Admin.";
        self::writeLogger($context, Logger::ALERT);
    }

    /*
     * @function rejudge
     * @input $uid, $problemId
     *
     * @description splice rejudge info
     */
    public static function rejudge($uid, $problemId)
    {
        $context = "Admin " . $uid . " rejudged Problem " . $problemId;
        self::writeLogger($context, Logger::ALERT);
    }

    /*
     * @function changeVisibility
     * @input $uid, $problemId, $newStatus
     *
     * @description splice change visibility info
     */
    public static function changeVisibility($uid, $problemId, $newStatus)
    {
        $context = "Admin " . $uid . " changed Problem " . $problemId . "Visibility to " . $newStatus;
        self::writeLogger($context, Logger::ALERT);
    }


}
