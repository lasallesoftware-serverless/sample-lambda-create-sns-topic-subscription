<?php

/**
 * This file is part of the Lasalle Software Serverless Create SNS Topic and Subscription sample PHP Lambda function.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright  (c) 2021-2023 The South LaSalle Trading Corporation
 * @license    http://opensource.org/licenses/MIT
 * @author     Bob Bloom
 * @email      bob.bloom@lasallesoftware.ca
 * @link       https://phpserverlessproject.com
 * @link       https://packagist.org/packages/lasallesoftware-serverless/sample-lambda-create-sns-topic-subscription
 * @link       https://github.com/lasallesoftware-serverless/sample-lambda-create-sns-topic-subscription
 *
 */

if (isset($_SERVER['LAMBDA_TASK_ROOT'])) {
    // For Lambda
    require_once './../vendor/autoload.php';
} else {1
    // For your local development
    require_once '/var/task/vendor/autoload.php';
}


// Set the common vars
$region = 'us-east-1';   // same region that you specified in the serverless.yml
$version = '2010-03-31';
$awsAccountNumber = 123456789012;   // Your AWS account number, required for your topic's ARN


// SNS client, using the AWS SDK for PHP
if (isset($_SERVER['LAMBDA_TASK_ROOT'])) {

    // For Lambda
    $SnsClient = new \Aws\Sns\SnsClient([
        'region'  => $region,
        'version' => $version,
    ]);

} else {

    // For your local development
    $SnsClient = new \Aws\Sns\SnsClient([
        'profile' => 'default',   // Change this name if your local profile name is different
        'region'  => $region,
        'version' => $version,
    ]);
}


// Create the SNS Topic
$topicName = 'MyTopic';
$topicArn = 'arn:aws:sns:' . $region . ':' . $awsAccountNumber . ':' . $topicName;

try {
    $result = $SnsClient->createTopic([
        'Name' => $topicName,
    ]);
    var_dump($result);

} catch (\Aws\Exception\AwsException $e) {

    if (isset($_SERVER['LAMBDA_TASK_ROOT'])) {

        // for Lambda, log the error message
        error_log($e->getMessage());

    } else {

        // for your local development, output error message
        var_dump($e->getMessage());
    }
} 


// Create the SNS subscription
$protocol = 'sms';            // List of protocols at https://docs.aws.amazon.com/sns/latest/api/API_Subscribe.html
$endpoint = '+15555555555';   // Recipient's telephone number. Please use a real telephone number

try {
    $result = $SnsClient->subscribe([
        'Protocol' => $protocol,
        'Endpoint' => $endpoint,
        'ReturnSubscriptionArn' => true,
        'TopicArn' => $topicArn,
    ]);
    var_dump($result);

} catch (AwsException $e) {
    
    if (isset($_SERVER['LAMBDA_TASK_ROOT'])) {

        // for Lambda, log the error message
        error_log($e->getMessage());

    } else {

        // for your local development, output error message
        var_dump($e->getMessage());
    }
} 