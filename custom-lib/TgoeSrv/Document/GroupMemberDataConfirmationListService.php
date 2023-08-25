<?php
namespace TgoeSrv\Document;

use TgoeSrv\Member\MemberGroup;
use Aspose\Words\Model\UserInformation;
use Aspose\Words\Model\Requests\ExecuteMailMergeOnlineRequest;
use Aspose\Words\Model\FieldOptions;
use TgoeSrv\Member\Enums\MemberGroupCustomProperty;
use TgoeSrv\Member\Member;
use TgoeSrv\Tools\Logger;
use Aspose\Words\Model\Requests\SaveAsOnlineRequest;
use Aspose\Words\Model\PdfSaveOptionsData;
use Aspose\Words\Model\Response\SaveAsOnlineResponse;

class GroupMemberDataConfirmationListService
{

    private const TEMPLATE_FILE = 'GroupMemberDataConfirmationList.docx';

    public static function generateDocument(MemberGroup $group, array $members, bool $createPdf = false)
    {
        $memberCount = count($members);

        $membersFields = array();
        foreach ($members as $m) {
            /**
             *
             * @var Member $m
             * @var MemberGroup $grp
             */

            // find status
            $g = $m->getMemberGroups();
            $s = 'kein';
            foreach ($g as $grp) {
                if ($grp->isActiveMembershipFeeGroup()) {
                    $s = 'aktiv';
                    break;
                } else if ($grp->isMemberFeeGroup()) {
                    $s = 'passiv';
                }
            }

            $membersFields[] = [
                'lastname' => $m->getFamilyName(),
                'firstname' => $m->getFirstName(),
                'street' => $m->getStreet(),
                'city' => $m->getCity(),
                'email' => $m->getPrivateEmail(),
                'birthday' => date('d.m.Y', $m->getDateOfBirth()),
                'status' => $s
            ];
        }

        $emptyFields = array();
        for ($i = 0; $i < 5; $i ++) {
            $emptyFields[] = array(
                'dummy' => 1
            );
        }

        $data = [
            'groups' => [
                'groupname' => $group->getName(),
                'trainer' => $group->getCustomProperty(MemberGroupCustomProperty::TRAINER),
                'weekday' => $group->getCustomProperty(MemberGroupCustomProperty::WEEKDAY),
                'time' => $group->getCustomProperty(MemberGroupCustomProperty::TIME),
                'location' => $group->getCustomProperty(MemberGroupCustomProperty::LOCATION),
                'printdate' => date('d.m.Y'),
                'count' => $memberCount,
                'members' => $membersFields,
                'empty' => $emptyFields
            ]
        ];

        // write data to tempfile
        // TODO: isn't it possible to hand over as a string?
        $jsondata = json_encode($data);
        Logger::info('Mailmerge data: ' . $jsondata);

        $th = tmpfile();
        fwrite($th, $jsondata);
        $filedata = stream_get_meta_data($th);
        $requestData = $filedata['uri'];
        // fclose($th);

        $wordsApi = AsposeCloudFactory::getAsposeWords();

        $requestTemplate = AsposeCloudFactory::getTemplateDirectory(self::TEMPLATE_FILE);
        $requestOptions = new FieldOptions([
            "current_user" => new UserInformation([
                "name" => "TGÖ Online Services"
            ])
        ]);

        $mailMergeRequest = new ExecuteMailMergeOnlineRequest($requestTemplate, $requestData, $requestOptions, TRUE, NULL, NULL);
        $response = $wordsApi->executeMailMergeOnline($mailMergeRequest);

        if (! ($response instanceof \SplFileObject)) {
            Logger::error('Error while generating mailmerge document. Response is not a file.');
            return null;
        }

        // if no PDF requested, return word file path
        if (! $createPdf)
            return $response->getPathname();

        $requestSaveOptionsData = new PdfSaveOptionsData([
            "file_name" => 'test.pdf'
        ]);

        $saveAsPdfRequest = new SaveAsOnlineRequest($response, $requestSaveOptionsData, NULL, NULL, NULL, NULL);

        /**
         * @var SaveAsOnlineResponse $saoReponse
         */
        $saoReponse = $wordsApi->saveAsOnline($saveAsPdfRequest);
        $saoReponseDocuments = $saoReponse->getdocument();
        if (!is_array($saoReponseDocuments) || count($saoReponseDocuments)<1 ) {
            Logger::error('Error while converting document to PDF. Response documents list is empty.');
            return null;
        }
        
        $saoReponseDocument = array_shift($saoReponseDocuments);
        return $saoReponseDocument->getPathname();
    }
}

