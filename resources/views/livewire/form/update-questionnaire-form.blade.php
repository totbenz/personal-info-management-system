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
                                    <x-checkbox id="consanguinity_third_degree_yes" label="Yes" wire:model="consanguinity_third_degree" name="consanguinity_third_degree" value="true" required />
                                    <x-checkbox id="consanguinity_third_degree_no" label="No" wire:model="!consanguinity_third_degree" name="consanguinity_third_degree" value="false" required />
                                </div>
                            </div>
                        </div>
                        <div class="mx-5 w-[45rem] mt-3 flex space-x-3 items-center">
                            <div class="w-10/12">
                                <p class="text-sm font-medium">b. within the fourth degree (for Local Government Unit - Career Employees)?</p>
                            </div>
                            <div class="w-2/12">
                                <div class="flex space-x-8 items-center">
                                    <x-checkbox id="right-label" label="Yes" wire:model="consanguinity_fourth_degree" name="consanguinity_fourth_degree" value="true" required/>

                                    <x-checkbox id="right-label" label="No" wire:model="!consanguinity_fourth_degree" name="consanguinity_fourth_degree" value="false" required/>
                                </div>
                            </div>
                        </div>
                        <div class="mx-5 mt-2.5 w-[57.5rem] flex justify-end">
                            <span class="w-[20rem]">
                                <x-input wire:model="consanguinity_third_degree_details" class="form-control w-full" type="text" label="If YES, give details" id="consanguinity_third_degree_details"/>
                            </span>
                        </div>
                    </div>

                    <div class="mt-3 mb-6">
                        <div class="w-[45rem] mt-3 flex space-x-3 items-center">
                            <div class="w-10/12">
                                <p class="text-sm font-medium">Have you ever been found guilty of any administrative offense?</p>
                            </div>
                            <div class="w-2/12">
                                <div class="px-5 flex space-x-8 items-center">
                                    <x-checkbox id="right-label" label="Yes" wire:model="found_guilty_administrative_offense" name="found_guilty_administrative_offense" value="true" required/>

                                    <x-checkbox id="right-label" label="No" wire:model="!found_guilty_administrative_offense" name="found_guilty_administrative_offense" value="false" required/>
                                </div>
                            </div>
                        </div>
                        <div class="mx-5 mt-2.5 w-[57.5rem] flex justify-end">
                            <span class="w-[20rem]">
                                <x-input class="form-control w-full" type="text" label="If YES, give details" id="administrative_offense_details"/>
                            </span>
                        </div>
                        <div class=" w-[45rem] mt-3 flex space-x-3 items-center">
                            <div class="w-10/12">
                                <p class="text-sm font-medium">Have you been criminally charged before any court?</p>
                            </div>
                            <div class="w-2/12">
                                <div class="px-5 flex space-x-8 items-center">
                                    <x-checkbox id="right-label" label="Yes" wire:model="criminally_charged" name="criminally_charged" value="true" required/>

                                    <x-checkbox id="right-label" label="No" wire:model="!criminally_charged" name="criminally_charged" value="false" required/>
                                </div>
                            </div>
                        </div>
                        <div class="mx-5 mt-2.5 w-[57.5rem] flex justify-end">
                            <span class="w-[20rem]">
                                <x-input class="form-control w-full" type="text" label="If YES, give details" id="criminally_charged_details" wire:model="criminally_charged_details"/>
                            </span>
                        </div>
                    </div>

                    <div class="mt-3 mb-6">
                        <div class="w-[45rem] mt-3 flex space-x-3 items-center">
                            <div class="w-10/12">
                                <p class="text-sm font-medium">Have you ever been convicted of any crime or violation of any law, decree, ordinance or regulation by any court or tribunal?</p>
                            </div>
                            <div class="w-2/12">
                                <div class="px-5 flex space-x-8 items-center">
                                    <x-checkbox id="right-label" label="Yes" wire:model="convicted_crime" name="convicted_crime" value="true" required/>

                                    <x-checkbox id="right-label" label="No" wire:model="!convicted_crime" name="convicted_crime" value="false" required/>
                                </div>
                            </div>
                        </div>
                        <div class="mx-5 mt-2.5 w-[57.5rem] flex justify-end">
                            <span class="w-[20rem]">
                                <x-input class="form-control w-full" type="text" label="If YES, give details" id="convicted_crime_details" wire:model="convicted_crime_details"/>
                            </span>
                        </div>
                    </div>

                    <div class="mt-3 mb-6">
                        <div class="w-[45rem] mt-3 flex space-x-3 items-center">
                            <div class="w-10/12">
                                <p class="text-sm font-medium">Have you ever been separated from the service in any of the following modes: resignation, retirement, dropped from the rolls, dismissal, termination, end of term, finished contract or phased out (abolition) in the public or private sector?</p>
                            </div>
                            <div class="w-2/12">
                                <div class="px-5 flex space-x-8 items-center">
                                    <x-checkbox id="right-label" label="Yes" wire:model="separated_from_service" name="separated_from_service" value="true" required/>

                                    <x-checkbox id="right-label" label="No" wire:model="!separated_from_service" name="separated_from_service" value="false" required/>
                                </div>
                            </div>
                        </div>
                        <div class="mx-5 mt-2.5 w-[57.5rem] flex justify-end">
                            <span class="w-[20rem]">
                                <x-input class="form-control w-full" type="text" label="If YES, give details" id="separation_details"/>
                            </span>
                        </div>
                    </div>

                    <div class="mt-3 mb-6">
                        <div class="w-[45rem] mt-3 flex space-x-3 items-center">
                            <div class="w-10/12">
                                <p class="text-sm font-medium">Have you ever been a candidate in a national or local election held within the last year (except Barangay election)?</p>
                            </div>
                            <div class="w-2/12">
                                <div class="px-5 flex space-x-8 items-center">
                                    <x-checkbox id="right-label" label="Yes" wire:model="candidate_last_year" name="candidate_last_year" value="true" required/>

                                    <x-checkbox id="right-label" label="No" wire:model="!candidate_last_year" name="candidate_last_year" value="false" required/>
                                </div>
                            </div>
                        </div>
                        <div class="mx-5 mt-2.5 w-[57.5rem] flex justify-end">
                            <span class="w-[20rem]">
                                <x-input class="form-control w-full" type="text" label="If YES, give details" id="candidate_details" wire:model="candidate_details"/>
                            </span>
                        </div>
                        <div class="mx-5 w-[45rem] mt-3 flex space-x-3 items-center">
                            <div class="w-10/12">
                                <p class="text-sm font-medium">Have you resigned from the government service during the three (3)-month period before the last election to promote/actively campaign for a national or local candidate?</p>
                            </div>
                            <div class="w-2/12">
                                <div class="flex space-x-8 items-center">
                                    <x-checkbox id="right-label" label="Yes" wire:model="resigned_to_campaign" name="resigned_to_campaign" value="true" required/>

                                    <x-checkbox id="right-label" label="No" wire:model="!resigned_to_campaign" name="resigned_to_campaign" value="false" required/>
                                </div>
                            </div>
                        </div>
                        <div class="mx-5 mt-2.5 w-[57.5rem] flex justify-end">
                            <span class="w-[20rem]">
                                <x-input class="form-control w-full" type="text" label="If YES, give details" id="resigned_campaign_details" wire:model="resigned_campaign_details"/>
                            </span>
                        </div>
                    </div>

                    <div class="mt-3 mb-6">
                        <div class="mx-5 w-[45rem] mt-3 flex space-x-3 items-center">
                            <div class="w-10/12">
                                <p class="text-sm font-medium">Have you acquired the status of an immigrant or permanent resident of another country?</p>
                            </div>
                            <div class="w-2/12">
                                <div class="flex space-x-8 items-center">
                                    <x-checkbox id="right-label" label="Yes" wire:model="immigrant_status" name="immigrant_status" value="true" required/>

                                    <x-checkbox id="right-label" label="No" wire:model="!immigrant_status" name="immigrant_status" value="false" required/>
                                </div>
                            </div>
                        </div>
                        <div class="mx-5 mt-2.5 w-[57.5rem] flex justify-end">
                            <span class="w-[20rem]">
                                <x-input class="form-control w-full" type="text" label="If YES, give details (country): " id="immigrant_country_details"/>
                            </span>
                        </div>
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
                                    <x-checkbox id="right-label" label="Yes" wire:model="member_indigenous_group" name="member_indigenous_group" value="true" required/>

                                    <x-checkbox id="right-label" label="No" wire:model="!member_indigenous_group" name="member_indigenous_group" value="false" required/>
                                </div>
                            </div>
                        </div>
                        <div class="mx-5 mt-2.5 w-[57.5rem] flex justify-end">
                            <span class="w-[20rem]">
                                <x-input class="form-control w-full" type="text" label="If YES, give details" id="indigenous_group_details" wire:model="indigenous_group_details" />
                            </span>
                        </div>

                        <div class="mx-5 w-[45rem] mt-3 flex space-x-3 items-center">
                            <div class="w-10/12">
                                <p class="text-sm font-medium">b. Are you a person with disability?</p>
                            </div>
                            <div class="w-2/12">
                                <div class="flex space-x-8 items-center">
                                    <x-checkbox id="right-label" label="Yes" wire:model="person_with_disability" name="person_with_disability" value="true" required/>

                                    <x-checkbox id="right-label" label="No" wire:model="!person_with_disability" name="person_with_disability" value="false" required/>
                                </div>
                            </div>
                        </div>
                        <div class="mx-5 mt-2.5 w-[57.5rem] flex justify-end">
                            <span class="w-[20rem]">
                                <x-input class="form-control w-full" type="text" label="If YES, please specify ID No: " id="disability_id_no" wire:model="disability_id_no"/>
                            </span>
                        </div>

                        <div class="mx-5 w-[45rem] mt-3 flex space-x-3 items-center">
                            <div class="w-10/12">
                                <p class="text-sm font-medium">c. Are you a solo parent?</p>
                            </div>
                            <div class="w-2/12">
                                <div class="flex space-x-8 items-center">
                                    <x-checkbox id="right-label" label="Yes" wire:model="solo_parent" name="solo_parent" value="true" required/>

                                    <x-checkbox id="right-label" label="No" wire:model="!solo_parent" name="solo_parent" value="false" required/>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="my-5 p-0 flex space-x-3 justify-end">
        <div class="w-2/12">
            <x-button wire:click.prevent="cancel" label="Cancel" class="px-5 py-2.5 w-full bg-danger font-semibold text-xs text-white uppercase tracking-widest hover:hover:bg-red-600 hover:scale-105 duration-150"/>
        </div>
        <div class="w-2/12">
            <x-button wire:click.prevent="save" label="Save" class="px-5 py-2.5 w-full bg-main font-semibold text-xs text-white uppercase tracking-widest hover:hover:bg-main-hover"/>
        </div>
    </div>
</div>
