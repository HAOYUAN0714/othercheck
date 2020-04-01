<?php

namespace App\Http\Controllers;

use App\Business\IMSport;
use App\Business\LiveStream;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LiveStreamController extends Controller
{
    // 取得直播賽事清單
    public function getLiveList(Request $request, LiveStream $liveStream)
    {
        try {
            $validator = Validator::make($request->all(), [
                'startDate' => 'date',
                'endDate' => 'date|after:startDate',
            ]);

            if ($validator->fails()) {
                throw new \Exception('参数输入错误', 112033000);
            }

            // 預設美東 今、明天
            $startDate = $request->input('startDate', date('Y-m-d\T00:00:00', strtotime('-4 hour')));
            $endDate = $request->input('endDate', date('Y-m-d\T23:59:59', strtotime('1 day -4 hour')));

            $data = $liveStream->getLiveList($startDate, $endDate);

            $result = [
                'code' => 200,
                'msg' => 'Success',
                'data' => $data
            ];
            return Response()->json($result);
        } catch (\Exception $error) {
            $result = [
                'code' => $error->getCode(),
                'msg' => $error->getMessage(),
                'data' => ''
            ];
            return Response()->json($result);
        }
    }

    // 取得賽事清單
    public function getEventList(Request $request, LiveStream $liveStream)
    {
        try {
            $validator = Validator::make($request->all(), [
                'startDate' => 'date',
                'endDate' => 'date|after:startDate',
            ]);

            if ($validator->fails()) {
                throw new \Exception('参数输入错误', 112033000);
            }

            // 預設美東 今、明天
            $startDate = $request->input('startDate', date('Y-m-d\T00:00:00', strtotime('-4 hour')));
            $endDate = $request->input('endDate', date('Y-m-d\T23:59:59', strtotime('1 day -4 hour')));

            $data = $liveStream->getEventList($startDate, $endDate);

            $result = [
                'code' => 200,
                'msg' => 'Success',
                'data' => $data
            ];
            return Response()->json($result);
        } catch (\Exception $error) {
            $result = [
                'code' => $error->getCode(),
                'msg' => $error->getMessage(),
                'data' => ''
            ];
            return Response()->json($result);
        }
    }

    // 手動對應直播
    public function correspond(Request $request, LiveStream $liveStream)
    {
        try {
            $validator = Validator::make($request->all(), [
                'imEventId' => 'required|integer',
                'liveEventId' => 'required|integer'
            ]);

            if ($validator->fails()) {
                throw new \Exception('参数输入错误', 112033000);
            }

            $imEventId = $request->input('imEventId');
            $liveEventId = $request->input('liveEventId');

            $data = $liveStream->correspondLiveStream($imEventId, $liveEventId);

            $result = [
                'code' => 200,
                'msg' => 'Success',
                'data' => $data
            ];
            return Response()->json($result);
        } catch (\Exception $error) {
            $result = [
                'code' => $error->getCode(),
                'msg' => $error->getMessage()
            ];
            return Response()->json($result);
        }
    }

    // 取消對應直播
    public function cancelCorrespond(Request $request, LiveStream $liveStream)
    {
        try {
            $validator = Validator::make($request->all(), [
                'eventId' => 'required|integer',
                'type' => 'required|in:1,2' # 1=imEventId, 2=liveEventId
            ]);

            if ($validator->fails()) {
                throw new \Exception('参数输入错误', 112033000);
            }

            $eventId = $request->input('eventId');
            $type = $request->input('type');

            $data = $liveStream->cancelCorrespondLiveStream($eventId, $type);

            $result = [
                'code' => 200,
                'msg' => 'Success',
                'data' => $data
            ];
            return Response()->json($result);
        } catch (\Exception $error) {
            $result = [
                'code' => $error->getCode(),
                'msg' => $error->getMessage()
            ];
            return Response()->json($result);
        }
    }

    public function getAllEventCount(Request $request, IMSport $IMSport)
    {
        try {
            $validator = Validator::make($request->all(), [
                'LanguageCode' => 'in:ENG,CHS,JAP,KOR,TH,VN,ID'
            ]);

            if ($validator->fails()) {
                throw new \Exception('参数输入错误', 112033000);
            }

            $languageCode = $request->cookie('abs-lang') ?? $request->input('LanguageCode') ?? 'CHS';

            $data = $IMSport->getAllEventCount($languageCode);

            $result = [
                'code' => 200,
                'msg' => 'Success',
                'data' => $data
            ];
            return Response()->json($result);
        } catch (\Exception $error) {
            $result = [
                'code' => $error->getCode(),
                'msg' => $error->getMessage()
            ];
            return Response()->json($result);
        }
    }

    public function getEventInfoByPage(Request $request, LiveStream $liveStream)
    {
        try {
            $validator = Validator::make($request->all(), [
                'SportId' => 'required|integer',
                'Market' => 'required|in:1,2,3',
                'EventDate' => 'date',
                'BetTypeIds' => 'array',
                'BetTypeIds.*' => 'integer',
                'PeriodIds' => 'array',
                'PeriodIds.*' => 'integer',
                'OddsType' => 'in:1,2,3,4',
                'OrderBy' => 'in:1,2',
                'CompetitionIds' => 'array',
                'CompetitionIds.*' => 'integer',
                'Page' => 'required|integer',
                'ColorCode' => 'alpha_num|size:2',
                'LanguageCode' => 'in:ENG,CHS,JAP,KOR,TH,VN,ID'
            ]);

            if ($validator->fails()) {
                throw new \Exception('参数输入错误', 112033000);
            }

            $sportId = $request->input('SportId');
            $market = $request->input('Market');
            $eventDate = $request->input('EventDate');
            $betTypeIds = $request->input('BetTypeIds');
            $periodIds = $request->input('PeriodIds');
            $oddsType = $request->input('OddsType', 2);
            $orderBy = $request->input('OrderBy');
            $competitionIds = $request->input('CompetitionIds');
            $page = $request->input('Page');
            $colorCode = $request->input('ColorCode', false); # 調色係數 (80較好看)
            $languageCode = $request->cookie('abs-lang') ?? $request->input('LanguageCode') ?? 'CHS';

            $data = $liveStream->getEventInfoByPage(
                $sportId,
                $market,
                $oddsType,
                $page,
                $languageCode,
                $eventDate,
                $betTypeIds,
                $orderBy,
                $periodIds,
                $competitionIds,
                $colorCode
            );

            $result = [
                'code' => 200,
                'msg' => 'Success',
                'data' => $data
            ];
            return Response()->json($result);
        } catch (\Exception $error) {
            $result = [
                'code' => $error->getCode(),
                'msg' => $error->getMessage()
            ];
            return Response()->json($result);
        }
    }

    public function getLiveEventInfo(Request $request, LiveStream $liveStream)
    {
        try {
            $validator = Validator::make($request->all(), [
                'SportIds' => 'required|array',
                'SportIds.*' => 'integer',
                'BetTypeIds' => 'array',
                'BetTypeIds.*' => 'integer',
                'PeriodIds' => 'array',
                'PeriodIds.*' => 'integer',
                'OddsType' => 'in:1,2,3,4',
                'OrderBy' => 'in:1,2',
                'CompetitionIds' => 'array',
                'CompetitionIds.*' => 'integer',
                'ColorCode' => 'alpha_num|size:2',
                'LanguageCode' => 'in:ENG,CHS,JAP,KOR,TH,VN,ID'
            ]);

            if ($validator->fails()) {
                throw new \Exception('参数输入错误', 112033000);
            }

            $sportIds = $request->input('SportIds');
            $betTypeIds = $request->input('BetTypeIds');
            $periodIds = $request->input('PeriodIds');
            $oddsType = $request->input('OddsType', 2);
            $orderBy = $request->input('OrderBy');
            $competitionIds = $request->input('CompetitionIds');
            $colorCode = $request->input('ColorCode', false); # 調色係數 (80較好看)
            $languageCode = $request->cookie('abs-lang') ?? $request->input('LanguageCode') ?? 'CHS';

            $data = $liveStream->getLiveEventInfo(
                $sportIds, $betTypeIds, $periodIds, $oddsType, $orderBy, $competitionIds, $languageCode, $colorCode
            );

            $result = [
                'code' => 200,
                'msg' => 'Success',
                'data' => $data
            ];
            return Response()->json($result);
        } catch (\Exception $error) {
            $result = [
                'code' => $error->getCode(),
                'msg' => $error->getMessage()
            ];
            return Response()->json($result);
        }
    }

    public function getSelectedEventInfo(Request $request, LiveStream $liveStream)
    {
        try {
            $validator = Validator::make($request->all(), [
                'SportId' => 'required|integer',
                'EventIds' => 'required|array',
                'EventIds.*' => 'required|integer',
                'OddsType' => 'in:1,2,3,4',
                'BetTypeIds' => 'array',
                'BetTypeIds.*' => 'integer',
                'PeriodIds' => 'array',
                'PeriodIds.*' => 'integer',
                'LanguageCode' => 'in:ENG,CHS,JAP,KOR,TH,VN,ID'
            ]);

            if ($validator->fails()) {
                throw new \Exception('参数输入错误', 112033000);
            }

            $sportId = $request->input('SportId');
            $eventIds = $request->input('EventIds');
            $oddsType = $request->input('OddsType', 2);
            $languageCode = $request->cookie('abs-lang') ?? $request->input('LanguageCode') ?? 'CHS';
            $betTypeIds = $request->input('BetTypeIds');
            $periodIds = $request->input('PeriodIds');

            $data = $liveStream->getSelectedEventInfo(
                $sportId, $eventIds, $oddsType, $languageCode, $betTypeIds, $periodIds
            );

            $result = [
                'code' => 200,
                'msg' => 'Success',
                'data' => $data
            ];
            return Response()->json($result);
        } catch (\Exception $error) {
            $result = [
                'code' => $error->getCode(),
                'msg' => $error->getMessage()
            ];
            return Response()->json($result);
        }
    }
}
