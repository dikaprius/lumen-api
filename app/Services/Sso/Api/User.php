<?php

namespace App\Services\Sso\Api;

use App\Http\Country;
use App\Http\ErrorResponses;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class User extends AbstractApi
{
    use ErrorResponses;

    /**
     * Hit the SSO endpoint to add Student to SSO Database
     *
     * @param $request
     * @return mixed
     */
    public function addStudent($request, $PTScore, $certStudying, $password = null)
    {
        $response = $this->adapter->post(
            sprintf('%s%s', $this->endpoint, config('sso.user')),
            [
                'headers' => [
                    'Content-Type' => 'application/json'
                ],

                'json' => [
                    'username' => $request->input('username'),
                    'password' => $password,
                    'acl' => [
                        'account' => [
                            'active' => \App\User::STATUS_ENABLED,
                            'expired_at' => Carbon::now()->addYear(1),

                            'start_at' => Carbon::now() ?: "",

                            "type" => "regular"
                        ],

                        'live' => [
                            'access' => [
                                \App\User::STATUS_ENABLED,
                            ],
                            'roles' => [
                                \App\User::ROLE_STUDENT
                            ]
                        ],

                        'dsa' => [
                            'roles' => [
                                \App\User::ROLE_STUDENT
                            ],
                            'access' => [
                                \App\User::STATUS_ENABLED
                            ]
                        ],

                        'live_portal' => [
                            'access' => [
                                \App\User::STATUS_ENABLED,
                            ],
                            'roles' => [
                                \App\User::ROLE_STUDENT
                            ]
                        ],

                        'mna' => [
                            'access' => [
                                \App\User::STATUS_ENABLED,
                            ],
                            'roles' => [
                                \App\User::ROLE_STUDENT
                            ]
                        ],

                        'dashboard' => [
                            'access' => [
                                \App\User::STATUS_DISABLED,
                            ],
                            'roles' => [
                                \App\User::ROLE_STUDENT
                            ]
                        ],

                        'voucher' => [
                            'access' => [
                                \App\User::STATUS_DISABLED,
                            ],
                            'roles' => [
                                \App\User::ROLE_STUDENT
                            ]
                        ],

                        'etest' => [
                            'access' => [
                                \App\User::STATUS_DISABLED,
                            ],
                            'roles' => [
                                \App\User::ROLE_STUDENT
                            ]
                        ],

                    ],
                    'profile' => [
                        'avatar' => "https://dyned.com/wp-content/uploads/2018/06/DynEd-Logo-Final_0_0.png",
                        'email' => $request->input('username'),
                        'fullname' => $request->input('fullname'),
                        'nickname' => $request->input('nickname'),
                        'gender' => $request->input('gender') ?: '',
                        'date_of_birth' => $request->input('date_of_birth') ?: '1990-01-01',
                        'country_iso' => Country::getIso($request) ?: '',
                        'dial_code' => Country::getDialCode($request) ?: '',
                        'phone' => $request->input('phone') ?: '',
                        'timezone' => $request->input('timezone') ?: '',
                        'dyned_pro_id' => $request->input('dyned_pro_id') ?: '',
                        'server_dyned_pro' => $request->input('server_dyned_pro') ?: '',
                        'pt_score' => $PTScore ?: '',
                        'cert_studying' => $certStudying ?: '',
                        'partner_id' => Auth::user()->profile()['partner_id'] ?: '',
                        'subgroup_id' => $request->input('subgroup_id') ?: '',
                        'institution' => [
                            'code' => 'neo',
                            'description' => "Nextgen english Online",
                            'logo' => "https://dyned.com/wp-content/uploads/2018/06/DynEd-Logo-Final_0_0.png",
                            'name' => "Nextgen English Online"
                        ]
                    ]
                ]
            ]
        );

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * Hit the SSO endpoint to add Student to SSO Database
     *
     * @param Request $request
     * @return mixed
     */
    public function addCoach(Request $request, $PTScore)
    {
        $response = $this->adapter->post(
            sprintf('%s%s', $this->endpoint, config('sso.user')),
            [
                'headers' => [
                    'Content-Type' => 'application/json'
                ],

                'json' => [
                    'username' => $request->input('username'),
                    'password' => $request->input('password'),
                    'acl' => [
                        'account' => [
                            'active' => \App\User::STATUS_DISABLED,
                            'expired_at' => Carbon::now()->addYear(1),
                            'start_at' => Carbon::now() ?: "",

                            "type" => "regular"
                        ],

                        'dsa' => [
                            'access' => [
                                \App\User::STATUS_DISABLED,
                            ],
                            'roles' => [
                                \App\User::ROLE_COACH
                            ]
                        ],

                        'live' => [
                            'access' => [
                                \App\User::STATUS_DISABLED,
                            ],
                            'roles' => [
                                \App\User::ROLE_COACH
                            ]
                        ],

                        'live_portal' => [
                            'access' => [
                                \App\User::STATUS_DISABLED,
                            ],
                            'roles' => [
                                \App\User::ROLE_COACH
                            ]
                        ],

                        'mna' => [
                            'access' => [
                                \App\User::STATUS_DISABLED,
                            ],
                            'roles' => [
                                \App\User::ROLE_COACH
                            ]
                        ],

                        'dashboard' => [
                            'access' => [
                                \App\User::STATUS_DISABLED,
                            ],
                            'roles' => [
                                \App\User::ROLE_COACH
                            ]
                        ],

                        'voucher' => [
                            'access' => [
                                \App\User::STATUS_DISABLED,
                            ],
                            'roles' => [
                                \App\User::ROLE_COACH
                            ]
                        ],

                        'etest' => [
                            'access' => [
                                \App\User::STATUS_DISABLED,
                            ],
                            'roles' => [
                                \App\User::ROLE_COACH
                            ]
                        ],

                    ],
                    'profile' => [
                        'avatar' => "https://dyned.com/wp-content/uploads/2018/06/DynEd-Logo-Final_0_0.png",
                        'email' => $request->input('username'),
                        'fullname' => $request->input('fullname'),
                        'nickname' => $request->input('nickname'),
                        'gender' => $request->input('gender'),
                        'date_of_birth' => $request->input('date_of_birth') ?: '1990-01-01',
                        'country_iso' => Country::getIso($request) ?: '',
                        'dial_code' => Country::getDialCode($request) ?: '',
                        'phone' => $request->input('phone'),
                        'coach_type' => $request->input('coach_type'),
                        'timezone' => $request->input('timezone') ?: '',
                        'dyned_pro_id' => $request->input('dyned_pro_id') ?: '',
                        'server_dyned_pro' => $request->input('server_dyned_pro') ?: '',
                        'pt_score' => $PTScore ?: '',
                        'coach_rating' => '',
                        'cert_studying' => $request->input('cert_studying') ?: '',
                        'partner_id' => Auth::user()->profile()['partner_id'] ?: '',
                        'subgroup_id' => $request->input('subgroup_id') ?: '',
                        'institution' => [
                            'code' => 'neo',
                            'description' => "Nextgen english Online",
                            'logo' => "https://dyned.com/wp-content/uploads/2018/06/DynEd-Logo-Final_0_0.png",
                            'name' => "Nextgen English Online"
                        ]
                    ]
                ]
            ]
        );

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * Add student partner to sso
     * @param Request $request
     * @param $id
     * @param $role
     * @param $status
     * @param int $access
     * @return mixed
     */
    public function addPartner(Request $request, $id, $role, $status, $access = \App\User::STATUS_ENABLED)
    {
        $response = $this->adapter->post(
            sprintf('%s%s', $this->endpoint, config('sso.user')),
            [
                'headers' => [
                    'Content-Type' => 'application/json'
                ],

                'json' => [
                    'username' => $request->input('username'),
                    'password' => $request->input('password'),
                    'acl' => [
                        'account' => [
                            'active' => $status,
                            'expired_at' => Carbon::now()->addYear(1),
                            'start_at' => Carbon::now() ?: "",
                            "type" => "regular"
                        ],

                        'dsa' => [
                            'access' => [
                                \App\User::STATUS_DISABLED,
                                $role
                            ],
                            'roles' => [
                                \App\User::ROLE_PARTNER
                            ]
                        ],

                        'live' => [
                            'access' => [
                                $access,
                                $role
                            ],
                            'roles' => [
                                \App\User::ROLE_PARTNER
                            ]
                        ],

                        'live_portal' => [
                            'access' => [
                                \App\User::STATUS_DISABLED,
                                $role
                            ],
                            'roles' => [
                                \App\User::ROLE_PARTNER
                            ]
                        ],

                        'mna' => [
                            'access' => [
                                \App\User::STATUS_DISABLED,
                                $role
                            ],
                            'roles' => [
                                \App\User::ROLE_PARTNER
                            ]
                        ],

                        'dashboard' => [
                            'access' => [
                                \App\User::STATUS_ENABLED,
                                $role
                            ],
                            'roles' => [
                                \App\User::ROLE_PARTNER
                            ],
                            'server' => [
                                $request->input('server') ?: ''
                            ],
                            'uic' => [
                                $request->input('uic') ?: ''
                            ]
                        ],

                        'voucher' => [
                            'access' => [
                                \App\User::STATUS_DISABLED,
                                $role
                            ],
                            'roles' => [
                                \App\User::ROLE_PARTNER
                            ]
                        ],

                        'etest' => [
                            'access' => [
                                \App\User::STATUS_DISABLED,
                                $role
                            ],
                            'roles' => [
                                \App\User::ROLE_PARTNER
                            ]
                        ],

                    ],
                    'profile' => [
                        'avatar' => "https://dyned.com/wp-content/uploads/2018/06/DynEd-Logo-Final_0_0.png",
                        'email' => $request->input('username'),
                        'fullname' => $request->input('fullname'),
                        'timezone' => $request->input('timezone') ?: '',
                        'partner_id' => $id,
                        'institution' => [
                            'code' => 'neo',
                            'description' => "Nextgen english Online",
                            'logo' => "https://dyned.com/wp-content/uploads/2018/06/DynEd-Logo-Final_0_0.png",
                            'name' => "Nextgen English Online"
                        ]
                    ]
                ]
            ]
        );

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @param $request
     * @return mixed
     *
     * Create/Add Admin into SSo
     */
    public function addAdmin($request, $status= \App\User::STATUS_ENABLED)
    {
        $response = $this->adapter->post(
            sprintf('%s%s', $this->endpoint, config('sso.user')),
            [
                'headers' => [
                    'Content-Type' => 'application/json'
                ],

                'json' => [
                    'username' => $request->input('username'),
                    'password' => $request->input('password'),
                    'acl' => [
                        'account' => [
                            'active' => \App\User::STATUS_ENABLED,
                            'expired_at' => Carbon::now()->addYear(1),
                            'start_at' => Carbon::now() ?: "",
                            "type" => "regular"
                        ],

                        'dsa' => [
                            'access' => [
                                \App\User::STATUS_DISABLED,
                            ],
                            'roles' => [
                                \App\User::ROLE_ADMIN
                            ]
                        ],

                        'live' => [
                            'access' => [
                                $status,
                            ],
                            'roles' => [
                                \App\User::ROLE_ADMIN
                            ]
                        ],

                        'live_portal' => [
                            'access' => [
                                \App\User::STATUS_DISABLED,
                            ],
                            'roles' => [
                                \App\User::ROLE_ADMIN
                            ]
                        ],

                        'mna' => [
                            'access' => [
                                \App\User::STATUS_DISABLED,
                            ],
                            'roles' => [
                                \App\User::ROLE_ADMIN
                            ]
                        ],

                        'dashboard' => [
                            'access' => [
                                \App\User::STATUS_ENABLED,
                            ],
                            'roles' => [
                                \App\User::ROLE_ADMIN
                            ],
                            'server' => [
                                $request->input('server') ?: ''
                            ],
                            'uic' => [
                                $request->input('uic') ?: ''
                            ]
                        ],

                        'voucher' => [
                            'access' => [
                                \App\User::STATUS_DISABLED,
                            ],
                            'roles' => [
                                \App\User::ROLE_ADMIN
                            ]
                        ],

                        'etest' => [
                            'access' => [
                                \App\User::STATUS_DISABLED,
                            ],
                            'roles' => [
                                \App\User::ROLE_ADMIN
                            ]
                        ],

                    ],
                    'profile' => [
                        'avatar' => "https://dyned.com/wp-content/uploads/2018/06/DynEd-Logo-Final_0_0.png",
                        'email' => $request->input('username'),
                        'fullname' => $request->input('fullname'),
                        'timezone' => $request->input('timezone') ?: '',
                        'institution' => [
                            'code' => 'neo',
                            'description' => "Nextgen english Online",
                            'logo' => "https://dyned.com/wp-content/uploads/2018/06/DynEd-Logo-Final_0_0.png",
                            'name' => "Nextgen English Online"
                        ]
                    ]
                ]
            ]
        );

        return json_decode($response->getBody()->getContents(), true);
    }
}
