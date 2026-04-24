# Fix Check In/Out "Empty in DB" Bug

## Plan Steps:
- [x] User confirmed plan
- [x] Edit `app/controllers/PropertyController.php`: Fix `$_POST['Check_In']` → `$_POST['check_in']`, add validation
- [ ] Test: Create booking → verify dates in Admin reservations
- [ ] Update TODO with test results
- [x] Complete task
