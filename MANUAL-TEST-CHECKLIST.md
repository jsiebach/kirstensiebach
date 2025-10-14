# Manual Testing Checklist - Filament Admin Panel

**Estimated Time:** 15 minutes
**Purpose:** Complete verification of CREATE, DELETE, and Validation functionality

---

## Prerequisites

- [ ] Access to http://localhost:8080/filament
- [ ] Login credentials: jsiebach@gmail.com / admin
- [ ] This checklist open for tracking

---

## Test 1: CREATE Operation (5 minutes)

**Resource to Test:** CV Page (or any page resource)

### Steps:

1. [ ] Navigate to CV Page resource
2. [ ] Click "New cv page" button
3. [ ] Fill in the form:
   - [ ] Title: "Manual Test CV Page"
   - [ ] Slug: Should auto-fill to "manual-test-cv-page"
   - [ ] Meta Title: "Test Meta Title"
   - [ ] Meta Description: "Test meta description created manually"
4. [ ] Click "Create" button
5. [ ] Verify:
   - [ ] Success notification appears
   - [ ] Redirected to list view OR edit view
   - [ ] New record visible in list with title "Manual Test CV Page"

**Result:** ✅ PASS / ❌ FAIL

**Notes:**
```
(Write any observations here)
```

---

## Test 2: DELETE Operation (3 minutes)

**Resource:** CV Page (delete the test record created above)

### Steps:

1. [ ] Navigate to CV Page resource list
2. [ ] Find the "Manual Test CV Page" record
3. [ ] Click the delete button (trash icon or action menu)
4. [ ] Verify:
   - [ ] Confirmation dialog appears
   - [ ] Dialog asks "Are you sure?"
5. [ ] Click "Confirm" or "Delete"
6. [ ] Verify:
   - [ ] Success notification appears
   - [ ] Record removed from list
   - [ ] Record count updated

**Result:** ✅ PASS / ❌ FAIL

**Notes:**
```
(Write any observations here)
```

---

## Test 3: Form Validation - Required Fields (3 minutes)

**Resource:** Photography Page (or any page resource)

### Steps:

1. [ ] Navigate to Photography Page resource
2. [ ] Click "New photography page" button
3. [ ] Leave ALL fields empty
4. [ ] Click "Create" button
5. [ ] Verify:
   - [ ] Form does NOT submit
   - [ ] Error messages appear on required fields
   - [ ] Errors clearly indicate which fields are required
   - [ ] "Title" field should show required error
   - [ ] Form remains on create page

**Result:** ✅ PASS / ❌ FAIL

**Notes:**
```
(Write any observations here)
```

---

## Test 4: Form Validation - Duplicate Slug (4 minutes)

**Resource:** Any page resource with existing data

### Steps:

1. [ ] Navigate to Home Page resource list
2. [ ] Note the slug of the existing record (should be "home")
3. [ ] Click "New home page" button
4. [ ] Fill in form:
   - [ ] Title: "Test Duplicate"
   - [ ] Slug: "home" (same as existing record)
   - [ ] Meta Title: "Test"
   - [ ] Meta Description: "Test"
5. [ ] Click "Create" button
6. [ ] Verify:
   - [ ] Form does NOT submit OR
   - [ ] Error message appears indicating duplicate slug OR
   - [ ] Database constraint error appears
   - [ ] Original record is not overwritten

**Result:** ✅ PASS / ❌ FAIL / ⚠️ NOT ENFORCED

**Notes:**
```
(Write any observations here)
```

---

## Bonus Test 5: File Upload (Optional, 5 minutes)

**Resource:** Home Page

### Steps:

1. [ ] Navigate to Home Page resource
2. [ ] Click "Edit" on existing Home Page record
3. [ ] Find "Banner Image" field
4. [ ] Click "Browse" or drag & drop a small image file (< 2MB)
5. [ ] Verify:
   - [ ] Image uploads successfully
   - [ ] Preview/thumbnail appears
   - [ ] File name displays
6. [ ] Click "Save changes"
7. [ ] Verify:
   - [ ] Success notification appears
   - [ ] Return to edit page
   - [ ] Uploaded image still shows
8. [ ] Test deletion:
   - [ ] Find remove/delete button for the image
   - [ ] Remove the image
   - [ ] Save again
   - [ ] Verify image removed successfully

**Result:** ✅ PASS / ❌ FAIL / ⏭️ SKIPPED

**Notes:**
```
(Write any observations here)
```

---

## Summary

**Date Tested:** _______________
**Tester Name:** _______________

**Results:**
- Test 1 (CREATE): ____
- Test 2 (DELETE): ____
- Test 3 (Validation - Required): ____
- Test 4 (Validation - Duplicate): ____
- Test 5 (File Upload - Optional): ____

**Overall Assessment:**
```
(Your final assessment here)
```

**Ready for Production?** ✅ YES / ❌ NO / ⚠️ WITH NOTES

**Action Items:**
```
(List any issues found that need to be fixed)
```

---

## Issue Tracking Template

If you find any issues, document them here:

### Issue #1
- **Severity:** 🔴 Critical / 🟡 Major / 🟢 Minor
- **Area:** CREATE / READ / UPDATE / DELETE / Validation / Other
- **Description:**
- **Steps to Reproduce:**
  1.
  2.
  3.
- **Expected Behavior:**
- **Actual Behavior:**
- **Screenshot:** (if applicable)

---

**Note:** If all tests pass, the admin panel is fully verified and production-ready! 🎉
