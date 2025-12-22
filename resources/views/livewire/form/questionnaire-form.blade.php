<div class="mx-auto py-8 px-10" >
    @if (!$updateMode)
        <section>
            <div class="flex justify-between">
                <h4 class="font-bold text-2xl text-gray-darkest">Questionnaire</h4>

                <button wire:click.prevent="edit" type="button" class="inline-flex items-center px-5 py-2 mb-2 mr-2 text-sm font-medium text-center text-white bg-main border border-main rounded-lg hover:bg-main_hover hover:scale-105 duration-300">
                    <span class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mr-2 -ml-1 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                        </svg>
                        <p>Edit</p>
                    </span>
                </button>
            </div>

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
                                <input type="radio" id="third-degree-yes" wire:model="consanguinity_third_degree" value="1" {{ $consanguinity_third_degree == 1 ? 'checked' : '' }} disabled>
                                <label for="third-degree-yes">Yes</label>
                                <input type="radio" id="third-degree-no" wire:model="consanguinity_third_degree" value="0" {{ $consanguinity_third_degree == 0 ? 'checked' : '' }} disabled>
                                <label for="third-degree-no">No</label>
                            </div>
                        </div>
                    </div>
                    <div class="mx-5 w-[45rem] mt-3 flex space-x-3 items-center">
                        <div class="w-10/12">
                            <p class="text-sm font-medium">b. within the fourth degree (for Local Government Unit - Career Employees)?</p>
                        </div>
                        <div class="w-2/12">
                            <div class="flex space-x-8 items-center">
                                <input type="radio" id="fourth-degree-yes" wire:model="consanguinity_fourth_degree" value="1" {{ $consanguinity_fourth_degree == 1 ? 'checked' : '' }} disabled>
                                <label for="fourth-degree-yes">Yes</label>
                                <input type="radio" id="fourth-degree-no" wire:model="consanguinity_fourth_degree" value="0" {{ $consanguinity_fourth_degree == 0 ? 'checked' : '' }} disabled>
                                <label for="fourth-degree-no">No</label>
                            </div>
                        </div>
                    </div>
                    @if($consanguinity_third_degree_details)
                    <div class="mx-5 mt-2.5 w-[57.5rem] flex justify-end">
                        <span class="w-[20rem]">
                            <x-input class="form-control w-full" type="text" label="If YES, give details" name="consanguinity_third_degree_details" wire:model="consanguinity_third_degree_details" disabled/>
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
                                <input type="radio" id="found_guilty_administrative_offense" wire:model="found_guilty_administrative_offense" value="1" {{ $found_guilty_administrative_offense == 1 ? 'checked' : '' }} disabled>
                                <label for="found_guilty_administrative_offense_yes">Yes</label>
                                <input type="radio" id="found_guilty_administrative_offense" wire:model="found_guilty_administrative_offense" value="0" {{ $found_guilty_administrative_offense == 0 ? 'checked' : '' }} disabled>
                                <label for="found_guilty_administrative_offense_no">No</label>
                            </div>
                        </div>
                    </div>
                    @if($administrative_offense_details)
                    <div class="mx-5 mt-2.5 w-[57.5rem] flex justify-end">
                        <span class="w-[20rem]">
                            <x-input class="form-control w-full" type="text" label="If YES, give details" id="administrative_offense_details" wire:model="administrative_offense_details" disabled/>
                        </span>
                    </div>
                    @endif
                    <div class=" w-[45rem] mt-3 flex space-x-3 items-center">
                        <div class="w-10/12">
                            <p class="text-sm font-medium">Have you been criminally charged before any court?</p>
                        </div>
                        <div class="w-2/12">
                            <div class="px-5 flex space-x-8 items-center">
                                <input type="radio" id="criminally_charged" wire:model="criminally_charged" value="1" {{ $criminally_charged == 1 ? 'checked' : '' }} disabled>
                                <label for="criminally_charged_yes">Yes</label>
                                <input type="radio" id="criminally_charged" wire:model="criminally_charged" value="0" {{ $criminally_charged == 0 ? 'checked' : '' }} disabled>
                                <label for="criminally_charged_no">No</label>
                            </div>
                        </div>
                    </div>
                    @if($criminally_charged_details)
                    <div class="mx-5 mt-2.5 w-[57.5rem] flex justify-end">
                        <span class="w-[20rem]">
                            <x-input class="form-control w-full" type="text" label="If YES, give details" id="criminally_charged_details" wire:model="criminally_charged_details" disabled/>
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
                                <input type="radio" id="convicted_crime" wire:model="convicted_crime" value="1" {{ $convicted_crime == 1 ? 'checked' : '' }} disabled>
                                <label for="convicted_crime_yes">Yes</label>
                                <input type="radio" id="convicted_crime" wire:model="convicted_crime" value="0" {{ $convicted_crime == 0 ? 'checked' : '' }} disabled>
                                <label for="convicted_crime_no">No</label>
                            </div>
                        </div>
                    </div>
                    @if($convicted_crime_details)
                    <div class="mx-5 mt-2.5 w-[57.5rem] flex justify-end">
                        <span class="w-[20rem]">
                            <x-input class="form-control w-full" type="text" label="If YES, give details" id="convicted_crime_details" wire:model="convicted_crime_details" disabled/>
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
                                <input type="radio" id="separated_from_service" wire:model="separated_from_service" value="1" {{ $separated_from_service == 1 ? 'checked' : '' }} disabled>
                                <label for="separated_from_service_yes">Yes</label>
                                <input type="radio" id="separated_from_service" wire:model="separated_from_service" value="0" {{ $separated_from_service == 0 ? 'checked' : '' }} disabled>
                                <label for="separated_from_service_no">No</label>
                            </div>
                        </div>
                    </div>
                    @if($separation_details)
                    <div class="mx-5 mt-2.5 w-[57.5rem] flex justify-end">
                        <span class="w-[20rem]">
                            <x-input class="form-control w-full" type="text" label="If YES, give details" id="separation_details" wire:model="separation_details" disabled/>
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
                                <input type="radio" id="candidate_last_year" wire:model="candidate_last_year" value="1" {{ $candidate_last_year == 1 ? 'checked' : '' }} disabled>
                                <label for="candidate_last_year_yes">Yes</label>
                                <input type="radio" id="candidate_last_year" wire:model="candidate_last_year" value="0" {{ $candidate_last_year == 0 ? 'checked' : '' }} disabled>
                                <label for="candidate_last_year_no">No</label>
                            </div>
                        </div>
                    </div>
                    @if($candidate_details)
                    <div class="mx-5 mt-2.5 w-[57.5rem] flex justify-end">
                        <span class="w-[20rem]">
                            <x-input class="form-control w-full" type="text" label="If YES, give details" id="candidate_details" wire:model="candidate_details" disabled/>
                        </span>
                    </div>
                    @endif
                    <div class="mx-5 w-[45rem] mt-3 flex space-x-3 items-center">
                        <div class="w-10/12">
                            <p class="text-sm font-medium">Have you resigned from the government service during the three (3)-month period before the last election to promote/actively campaign for a national or local candidate?</p>
                        </div>
                        <div class="w-2/12">
                            <div class="flex space-x-8 items-center">
                                <input type="radio" id="resigned_to_campaign" wire:model="resigned_to_campaign" value="1" {{ $resigned_to_campaign == 1 ? 'checked' : '' }} disabled>
                                <label for="resigned_to_campaign_yes">Yes</label>
                                <input type="radio" id="resigned_to_campaign" wire:model="resigned_to_campaign" value="0" {{ $resigned_to_campaign == 0 ? 'checked' : '' }} disabled>
                                <label for="resigned_to_campaign_no">No</label>
                            </div>
                        </div>
                    </div>
                    @if($resigned_campaign_details)
                    <div class="mx-5 mt-2.5 w-[57.5rem] flex justify-end">
                        <span class="w-[20rem]">
                            <x-input class="form-control w-full" type="text" label="If YES, give details" id="resigned_campaign_details" wire:model="resigned_campaign_details" disabled/>
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
                                <input type="radio" id="immigrant_status" wire:model="immigrant_status" value="1" {{ $immigrant_status == 1 ? 'checked' : '' }} disabled>
                                <label for="immigrant_status_yes">Yes</label>
                                <input type="radio" id="immigrant_status" wire:model="immigrant_status" value="0" {{ $immigrant_status == 0 ? 'checked' : '' }} disabled>
                                <label for="immigrant_status_no">No</label>
                            </div>
                        </div>
                    </div>
                    @if($immigrant_country_details)
                    <div class="mx-5 mt-2.5 w-[57.5rem] flex justify-end">
                        <span class="w-[20rem]">
                            <x-input class="form-control w-full" type="text" label="If YES, give details (country): " id="immigrant_country_details" wire:model="immigrant_country_details" disabled/>
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
                                <input type="radio" id="member_indigenous_group" wire:model="member_indigenous_group" value="1" {{ $member_indigenous_group == 1 ? 'checked' : '' }} disabled>
                                <label for="member_indigenous_group_yes">Yes</label>
                                <input type="radio" id="member_indigenous_group" wire:model="member_indigenous_group" value="0" {{ $member_indigenous_group == 0 ? 'checked' : '' }} disabled>
                                <label for="member_indigenous_group_no">No</label>
                            </div>
                        </div>
                    </div>
                    @if($indigenous_group_details)
                    <div class="mx-5 mt-2.5 w-[57.5rem] flex justify-end">
                        <span class="w-[20rem]">
                            <x-input class="form-control w-full" type="text" label="If YES, give details" id="indigenous_group_details" wire:model="indigenous_group_details" disabled/>
                        </span>
                    </div>
                    @endif

                    <div class="mx-5 w-[45rem] mt-3 flex space-x-3 items-center">
                        <div class="w-10/12">
                            <p class="text-sm font-medium">b. Are you a person with disability?</p>
                        </div>
                        <div class="w-2/12">
                            <div class="flex space-x-8 items-center">
                                <input type="radio" id="person_with_disability" wire:model="person_with_disability" value="1" {{ $person_with_disability == 1 ? 'checked' : '' }} disabled>
                                <label for="person_with_disability_yes">Yes</label>
                                <input type="radio" id="person_with_disability" wire:model="person_with_disability" value="0" {{ $person_with_disability == 0 ? 'checked' : '' }} disabled>
                                <label for="person_with_disability_no">No</label>
                            </div>
                        </div>
                    </div>
                    @if($person_with_disability == 1)
                    <div class="mx-5 mt-2.5 w-[57.5rem] flex justify-end">
                        <span class="w-[20rem]">
                            <x-input class="form-control w-full" type="text" label="If YES, please specify ID No: " id="disability_id_no" wire:model="disability_id_no" value="{{ $disability_id_no }}" disabled/>
                        </span>
                    </div>
                    @endif

                    <div class="mx-5 w-[45rem] mt-3 flex space-x-3 items-center">
                        <div class="w-10/12">
                            <p class="text-sm font-medium">c. Are you a solo parent?</p>
                        </div>
                        <div class="w-2/12">
                            <div class="flex space-x-8 items-center">
                                <input type="radio" id="solo_parent" wire:model="solo_parent" value="1" {{ $solo_parent == 1 ? 'checked' : '' }} disabled>
                                <label for="solo_parent">Yes</label>
                                <input type="radio" id="solo_parent" wire:model="solo_parent" value="0" {{ $solo_parent == 0 ? 'checked' : '' }} disabled>
                                <label for="solo_parent">No</label>
                            </div>
                        </div>
                    </div>
                    @if($solo_parent == 1)
                    <div class="mx-5 mt-2.5 w-[57.5rem] flex justify-end">
                        <span class="w-[20rem]">
                            <x-input class="form-control w-full" type="text" label="If YES, please specify ID No: " id="solo_parent_id_no" wire:model="solo_parent_id_no" value="{{ $solo_parent_id_no }}" disabled/>
                        </span>
                    </div>
                    @endif
                </div>

            </div>
        </section>
    @else
        <div class="flex justify-between">
            <h4 class="font-bold text-2xl text-gray-darkest">Edit Questionnaire</h4>

            <button wire:click.prevent="back" type="button" class="inline-flex items-center px-5 py-2 mb-2 mr-2 text-sm font-medium text-center text-gray-900 bg-gray-50 border border-slate-200 rounded-lg hover:bg-white hover:scale-105 duration-300">
                <span class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mr-2 -ml-1 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                    </svg>

                    <p>Back</p>
                </span>
            </button>
        </div>

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
        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                <strong class="font-bold">Validation Errors:</strong>
                <ul class="mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="my-5 p-0 flex space-x-3 justify-end">
            <div class="w-2/12">
                <button wire:click.prevent="cancel" class="px-5 py-2.5 w-full bg-danger font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-600 hover:scale-105 duration-150 rounded">
                    Cancel
                </button>
            </div>
            <div class="w-2/12">
                <button wire:click.prevent="save" class="px-5 py-2.5 w-full bg-main font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-800 rounded">
                    Save
                </button>
            </div>
        </div>
    @endif
</div>
