<script>
import {DateTime} from 'luxon';
import {createEventDispatcher, onMount} from "svelte";
const dispatch  = createEventDispatcher();

export let iri = null;
let job = null;

onMount(() => {
    fetch(iri).then(r => r.json()).then(j => {
        job = j;
    })
})
</script>

{#if job !== null}
    <div class="border-bottom">
        <div class="container">
            <div class="row py-3">
                <div class="col-12 fs-5 fw-light">
                    { job.title }
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row py-3">
            <div class="col-12 fw-bold">
                <button type="button" class="btn btn-light"
                    on:click={() => dispatch('showList')}
                >Back</button>
            </div>
            <div class="col-12 fw-bold">
                Title:
            </div>
            <div class="col-12">
                { job.title }
            </div>
            <div class="col-12 fw-bold">
                Description:
            </div>
            <div class="col-12">
                { job.description }
            </div>
            <div class="col-12 fw-bold">
                Location:
            </div>
            <div class="col-12">
                { job.location }
            </div>
            <div class="col-12 fw-bold">
                Date:
            </div>
            <div class="col-12">
                { DateTime.fromISO(job.date).toISODate() }
            </div>
            <div class="col-12 fw-bold">
                Applicants
            </div>
            <div class="col-12">
                <ul>
                    {#each job.jobApplicants as jobApplicant}
                    <li>{ jobApplicant.applicant.name }</li>
                    {/each}
                </ul>
            </div>
        </div>
    </div>
{/if}