<?php
/**
 * Created by PhpStorm.
 * User: robertren
 * Date: 24/7/18
 * Time: 2:20 PM
 */

namespace App\Enum;

class PositionCandidateStatus
{
    const not_reviewed = "Not Reviewed";
    const interview_1_to_be_scheduled = "Interview 1 To Be Scheduled";
    const interview_1_scheduled = "Interview 1 Scheduled";
    const interview_1_performed = "Interview 1 Performed";
    const interview_2_to_be_scheduled = "Interview 2 To Be Scheduled";
    const interview_2_scheduled = "Interview 2 Scheduled";
    const interview_2_performed = "Interview 2 Performed";
    const appscore_test_sent = "Appscore Test Sent";
    const appscore_test_to_be_reviewed = "Appscore Test To Be Reviewed";
    const appscore_test_reviewed = "Appscore Test Reviewed";
    const candidate_profile_to_be_created = "Candidate Profile To Be Created";
    const candidate_profile_sent_to_client = "Candidate Profile Sent To Client";
    const client_interview_to_be_scheduled = "Client Interview To Be Scheduled";
    const client_interview_scheduled = "Client Interview Scheduled";
    const client_interview_performed = "Client Interview Performed";
    const client_test_sent = "Client Test Sent";
    const client_test_to_be_reviewed_by_client = "Client Test To Be Reviewed By Client";
    const client_accepted = "Client Accepted";
    const client_rejected = "Client Rejected";
    const apscore_rejected = "Appscore Rejected";
    const candidate_not_available = "Candidate Not Available";
}