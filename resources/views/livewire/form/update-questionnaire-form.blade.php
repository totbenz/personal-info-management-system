<div>
    <div class="mt-5">
        <div>
            <div class="mt-2">
                <div>
                    <div class="mt-3 mb-6">
                        <h6 class="text-sm mt-3 mb-2 font-medium">
                            Are you related to core appointing or re-appointing or recommending the promotion or office or to the person who has immediate supervision over Bureau or Department where you will be appointed.
                        </h6>
                        <div class="mx-5 w-[45rem] mt-3 flex space-x-3 items-center">
                            <div class="w-10/12">
                                <p class="text-sm font-medium">a. within the third degree?</p>
                            </div>
                            <div class="w-2/12">
                                <div class="flex space-x-8 items-center">
                                    <x-radio id="consanguinity_third_degree_yes" label="Yes" wire:model.live="consanguinity_third_degree" name="consanguinity_third_degree" value="1" />
                                    <x-radio id="consanguinity_third_degree_no" label="No" wire:model.live="consanguinity_third_degree" name="consanguinity_third_degree" value="0" />
                                </div>
                            </div>
                        </div>
                        <div class="mx-5 w-[45rem] mt-3 flex space-x-3 items-center">
                            <div class="w-10/12">
                                <p class="text-sm font-medium">b. within the fourth degree (for Local Government Unit - Career Employees)?</p>
                            </div>
                            <div class="w-2/12">
                                <div class="flex space-x-8 items-center">
                                    <x-radio id="consanguinity_fourth_degree_yes" label="Yes" wire:model.live="consanguinity_fourth_degree" name="consanguinity_fourth_degree" value="1" />
                                    <x-radio id="consanguinity_fourth_degree_no" label="No" wire:model.live="consanguinity_fourth_degree" name="consanguinity_fourth_degree" value="0" />
                                </div>
                            </div>
                        </div>
                        @if($consanguinity_third_degree == 1 || $consanguinity_fourth_degree == 1)
                        <div class="mx-5 mt-2.5 w-[57.5rem] flex justify-end">
                            <span class="w-[20rem]">
                                <x-input wire:model="consanguinity_third_degree_details" class="form-control w-full" type="text" label="If YES, give details" id="consanguinity_third_degree_details"/>
                            </span>
                        </div>
                        @endif
                    </div>

                    <div class="mt-3 mb-6">
                        <div class="w-[45rem] mt-3 flex space-x-3 items-center">
                            <div class="w-10/12">
                                <p class="text-sm font-medium">Have you ever been found guilty of any administrative offense?</p>
                            </div>
                            <div class="w-2/12">
                                <div class="px-5 flex space-x-8 items-center">
                                    <x-radio id="found_guilty_administrative_offense_yes" label="Yes" wire:model.live="found_guilty_administrative_offense" name="found_guilty_administrative_offense" value="1" />
                                    <x-radio id="found_guilty_administrative_offense_no" label="No" wire:model.live="found_guilty_administrative_offense" name="found_guilty_administrative_offense" value="0" />
                                </div>
                            </div>
                        </div>
                        @if($found_guilty_administrative_offense == 1)
                        <div class="mx-5 mt-2.5 w-[57.5rem] flex justify-end">
                            <span class="w-[20rem]">
                                <x-input wire:model="administrative_offense_details" class="form-control w-full" type="text" label="If YES, give details" id="administrative_offense_details"/>
                            </span>
                        </div>
                        @endif
                        <div class=" w-[45rem] mt-3 flex space-x-3 items-center">
                            <div class="w-10/12">
                                <p class="text-sm font-medium">Have you been criminally charged before any court?</p>
                            </div>
                            <div class="w-2/12">
                                <div class="px-5 flex space-x-8 items-center">
                                    <x-radio id="criminally_charged_yes" label="Yes" wire:model.live="criminally_charged" name="criminally_charged" value="1" />
                                    <x-radio id="criminally_charged_no" label="No" wire:model.live="criminally_charged" name="criminally_charged" value="0" />
                                </div>
                            </div>
                        </div>
                        @if($criminally_charged == 1)
                        <div class="mx-5 mt-2.5 w-[57.5rem] flex justify-end">
                            <span class="w-[20rem]">
                                <x-input class="form-control w-full" type="text" label="If YES, give details" id="criminally_charged_details" wire:model="criminally_charged_details"/>
                            </span>
                        </div>
                        @endif
                    </div>

                    <div class="mt-3 mb-6">
                        <div class="w-[45rem] mt-3 flex space-x-3 items-center">
                            <div class="w-10/12">
                                <p class="text-sm font-medium">Have you ever been convicted of any crime or violation of any law, decree, ordinance or regulation by any court or tribunal?</p>
                            </div>
                            <div class="w-2/12">
                                <div class="px-5 flex space-x-8 items-center">
                                    <x-radio id="convicted_crime_yes" label="Yes" wire:model.live="convicted_crime" name="convicted_crime" value="1" />
                                    <x-radio id="convicted_crime_no" label="No" wire:model.live="convicted_crime" name="convicted_crime" value="0" />
                                </div>
                            </div>
                        </div>
                        @if($convicted_crime == 1)
                        <div class="mx-5 mt-2.5 w-[57.5rem] flex justify-end">
                            <span class="w-[20rem]">
                                <x-input class="form-control w-full" type="text" label="If YES, give details" id="convicted_crime_details" wire:model="convicted_crime_details"/>
                            </span>
                        </div>
                        @endif
                    </div>

                    <div class="mt-3 mb-6">
                        <div class="w-[45rem] mt-3 flex space-x-3 items-center">
                            <div class="w-10/12">
                                <p class="text-sm font-medium">Have you ever been separated from the service in any of the following modes: resignation, retirement, dropped from the rolls, dismissal, termination, end of term, finished contract or phased out (abolition) in the public or private sector?</p>
                            </div>
                            <div class="w-2/12">
                                <div class="px-5 flex space-x-8 items-center">
                                    <x-radio id="separated_from_service_yes" label="Yes" wire:model.live="separated_from_service" name="separated_from_service" value="1" />
                                    <x-radio id="separated_from_service_no" label="No" wire:model.live="separated_from_service" name="separated_from_service" value="0" />
                                </div>
                            </div>
                        </div>
                        @if($separated_from_service == 1)
                        <div class="mx-5 mt-2.5 w-[57.5rem] flex justify-end">
                            <span class="w-[20rem]">
                                <x-input wire:model="separation_details" class="form-control w-full" type="text" label="If YES, give details" id="separation_details"/>
                            </span>
                        </div>
                        @endif
                    </div>

                    <div class="mt-3 mb-6">
                        <div class="w-[45rem] mt-3 flex space-x-3 items-center">
                            <div class="w-10/12">
                                <p class="text-sm font-medium">Have you ever been a candidate in a national or local election held within the last year (except Barangay election)?</p>
                            </div>
                            <div class="w-2/12">
                                <div class="px-5 flex space-x-8 items-center">
                                    <x-radio id="candidate_last_year_yes" label="Yes" wire:model.live="candidate_last_year" name="candidate_last_year" value="1" />
                                    <x-radio id="candidate_last_year_no" label="No" wire:model.live="candidate_last_year" name="candidate_last_year" value="0" />
                                </div>
                            </div>
                        </div>
                        @if($candidate_last_year == 1)
                        <div class="mx-5 mt-2.5 w-[57.5rem] flex justify-end">
                            <span class="w-[20rem]">
                                <x-input class="form-control w-full" type="text" label="If YES, give details" id="candidate_details" wire:model="candidate_details"/>
                            </span>
                        </div>
                        @endif
                        <div class="mx-5 w-[45rem] mt-3 flex space-x-3 items-center">
                            <div class="w-10/12">
                                <p class="text-sm font-medium">Have you resigned from the government service during the three (3)-month period before the last election to promote/actively campaign for a national or local candidate?</p>
                            </div>
                            <div class="w-2/12">
                                <div class="flex space-x-8 items-center">
                                    <x-radio id="resigned_to_campaign_yes" label="Yes" wire:model.live="resigned_to_campaign" name="resigned_to_campaign" value="1" />
                                    <x-radio id="resigned_to_campaign_no" label="No" wire:model.live="resigned_to_campaign" name="resigned_to_campaign" value="0" />
                                </div>
                            </div>
                        </div>
                        @if($resigned_to_campaign == 1)
                        <div class="mx-5 mt-2.5 w-[57.5rem] flex justify-end">
                            <span class="w-[20rem]">
                                <x-input class="form-control w-full" type="text" label="If YES, give details" id="resigned_campaign_details" wire:model="resigned_campaign_details"/>
                            </span>
                        </div>
                        @endif
                    </div>

                    <div class="mt-3 mb-6">
                        <div class="mx-5 w-[45rem] mt-3 flex space-x-3 items-center">
                            <div class="w-10/12">
                                <p class="text-sm font-medium">Have you acquired the status of an immigrant or permanent resident of another country?</p>
                            </div>
                            <div class="w-2/12">
                                <div class="flex space-x-8 items-center">
                                    <x-radio id="immigrant_status_yes" label="Yes" wire:model.live="immigrant_status" name="immigrant_status" value="1" />
                                    <x-radio id="immigrant_status_no" label="No" wire:model.live="immigrant_status" name="immigrant_status" value="0" />
                                </div>
                            </div>
                        </div>
                        @if($immigrant_status == 1)
                        <div class="mx-5 mt-2.5 w-[57.5rem] flex justify-end">
                            <span class="w-[20rem]">
                                <x-input wire:model="immigrant_country_details" class="form-control w-full" type="text" label="If YES, give details (country): " id="immigrant_country_details"/>
                            </span>
                        </div>
                        @endif
                    </div>

                    <div class="mt-3 mb-6">
                        <h6 class="text-sm my-3 mb-2 font-medium">
                            Pursuant to: (a) Indigenous People's Act (RA 8371); (b) Magna Carta for Disabled Persons (RA 7277); and (c) Solo Parents Welfare Act of 2000 (RA 8972), please answer the following items:
                        </h6>
                        <div class="mx-5 w-[45rem] mt-3 flex space-x-3 items-center">
                            <div class="w-10/12">
                                <p class="text-sm font-medium">a. Are you a member of any indigenous group?</p>
                            </div>
                            <div class="w-2/12">
                                <div class="flex space-x-8 items-center">
                                    <x-radio id="member_indigenous_group_yes" label="Yes" wire:model.live="member_indigenous_group" name="member_indigenous_group" value="1" />
                                    <x-radio id="member_indigenous_group_no" label="No" wire:model.live="member_indigenous_group" name="member_indigenous_group" value="0" />
                                </div>
                            </div>
                        </div>
                        @if($member_indigenous_group == 1)
                        <div class="mx-5 mt-2.5 w-[57.5rem] flex justify-end">
                            <span class="w-[20rem]">
                                <x-input class="form-control w-full" type="text" label="If YES, give details" id="indigenous_group_details" wire:model="indigenous_group_details" />
                            </span>
                        </div>
                        @endif

                        <div class="mx-5 w-[45rem] mt-3 flex space-x-3 items-center">
                            <div class="w-10/12">
                                <p class="text-sm font-medium">b. Are you a person with disability?</p>
                            </div>
                            <div class="w-2/12">
                                <div class="flex space-x-8 items-center">
                                    <x-radio id="person_with_disability_yes" label="Yes" wire:model.live="person_with_disability" name="person_with_disability" value="1" />
                                    <x-radio id="person_with_disability_no" label="No" wire:model.live="person_with_disability" name="person_with_disability" value="0" />
                                </div>
                            </div>
                        </div>
                        @if($person_with_disability == 1)
                        <div class="mx-5 mt-2.5 w-[57.5rem] flex justify-end">
                            <span class="w-[20rem]">
                                <x-input class="form-control w-full" type="text" label="If YES, please specify ID No: " id="disability_id_no" wire:model="disability_id_no"/>
                            </span>
                        </div>
                        @endif

                        <div class="mx-5 w-[45rem] mt-3 flex space-x-3 items-center">
                            <div class="w-10/12">
                                <p class="text-sm font-medium">c. Are you a solo parent?</p>
                            </div>
                            <div class="w-2/12">
                                <div class="flex space-x-8 items-center">
                                    <x-radio id="solo_parent_yes" label="Yes" wire:model.live="solo_parent" name="solo_parent" value="1" />
                                    <x-radio id="solo_parent_no" label="No" wire:model.live="solo_parent" name="solo_parent" value="0" />
                                </div>
                            </div>
                        </div>
                        @if($solo_parent == 1)
                        <div class="mx-5 mt-2.5 w-[57.5rem] flex justify-end">
                            <span class="w-[20rem]">
                                <x-input class="form-control w-full" type="text" label="If YES, please specify ID No: " id="solo_parent_id_no" wire:model="solo_parent_id_no"/>
                            </span>
                        </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
    </div>

