<div id='financial'>
    <div class="step1 row settlement">
        <h3>Settlement account</h3>
        <div class="mb-12 row">
            <label for="staticEmail" class="col-sm-2 col-form-label">IBAN</label>
            <div class="col-sm-10">
                <input type="text" class="form-control input" id="iban">
            </div>
        </div>
        <div class="mb-12 row">
            <label for="staticEmail" class="col-sm-2 col-form-label">SWIFT</label>
            <div class="col-sm-10">
                <input type="text" class="form-control input" id="swift">
            </div>
        </div>
        <div class="mb-12 row">
            <label for="staticEmail" class="col-sm-2 col-form-label">Screenshot from Bank</label>
            <div class="col-sm-10">
                <input type="file" class="form-control input" id="screenshot">
            </div>
            <div class="alert alert-warning">
                The Screnshot must contain:<br />
                <ol>
                    <li>Logo or name of bank</li>
                    <li>IBAN</li>
                    <li>SWIFT</li>
                    <li>Account holder (Your company name)</li>
                    <li>Date</li>
                </ol>
            </div>
        </div>
        <div class="mb-12 row">
            <label for="staticEmail" class="col-sm-2 col-form-label">Settlement Frequency</label>
            <div class="col-sm-10">
                <select class="form-control select">
                    <option>Weekly</option>
                    <option>Daily</option>
                    <option>Monthly</option>
                </select>
            </div>
        </div>
    </div>
    <div class="step2 row earlier_provider">
        <h2>Earlier Processing provider</h2>
        <p>We do collect data on your earlier payment provider. We do not ask why you want to change, but we need to see a copy of earlier processing.</p>
        <div class="mb-12 row">
            <label for="staticEmail" class="col-sm-2 col-form-label">Have you had an earlier provider</label>
            <div class="col-sm-10">
                <select class="form-control select">
                    <option>No</option>
                    <option>Yes</option>
                </select>
            </div>
        </div>
        <div class="mb-12 row">
            <label for="staticEmail" class="col-sm-2 col-form-label">Copy of processing history (3 months)</label>
            <div class="col-sm-10">
                <input type="file" class="form-control input" name="processing-history" id="processing-history">
            </div>
        </div>
        <div class="mb-12 row">
            <label for="staticEmail" class="col-sm-2 col-form-label">&nbsp;</label>
            <div class="col-sm-10">
                <button class="btn btn-success" data-group="website" data-href="checks" data-validation="UploadFinanceData">Start the onboarding flow</button>
            </div>
        </div>
    </div>
</div>
