<script>
import {DateTime} from 'luxon';
import { createEventDispatcher} from 'svelte';
export let jobs = [];

const dispatch  = createEventDispatcher();

</script>

<div class="border-bottom">
    <div class="container">
        <div class="row py-3">
            <div class="col-12 fs-5 fw-light">
                Job List
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="row my-2 fw-bold">
        <div class="col-12 col-md-6">
            Title
        </div>
        <div class="col-12 col-md-3">
            Location
        </div>
        <div class="col-12 col-md-3">
            Date
        </div>
    </div>
    {#each jobs as job, i}
        <div class="row my-2">
            <div class="col-12 col-md-6">
                <button class="" on:click={() => dispatch('showJob', {iri: job['@id']})}>{ job.title }</button>
            </div>
            <div class="col-12 col-md-3">
                { job.location }
            </div>
            <div class="col-12 col-md-3">
                { DateTime.fromISO(job.date).toISODate() }
            </div>
        </div>
    {/each}
</div>

<style>
    button {
        border: 0;
        background: none;
        color: #0d6efd;
        text-decoration: underline;
        padding-left: 0;
        padding-right: 0;
    }
</style>