# EVAGU compatibility with Moodle 3.5

This branch contains compatibility adjustments for the EVAGU theme running on
Moodle 3.5.17+.

## Main fixes

- Replaced post-3.5 course category API calls (`core_course_category`) with
  Moodle 3.5's `coursecat` API.
- Added explicit loading of `lib/coursecatlib.php` where EVAGU accesses
  course categories directly.
- Changed the Moodle 3.x header path to use Moodle 3.5's native
  `core_renderer::full_header()`, avoiding Moodle 4.x-only header APIs.
- Guarded secondary/primary navigation code so Moodle 4.x navigation classes
  and methods are never invoked on Moodle 3.5.
- Guarded `flat_navigation::get_collectionlabel()`, which is not available in
  this Moodle 3.5 installation.
- Fixed handling of the root pseudo-category (id 0) in the custom course
  renderer.
- Fixed overview-image pluginfile paths by including the file item id.

## Runtime validation checklist

After deploying this branch to the Moodle 3.5 environment:

1. Purge all Moodle caches.
2. Open the front page while logged out.
3. Log in as administrator.
4. Open Site administration.
5. Open the course category index and at least one category.
6. Open an individual course and a course activity.
7. Open Dashboard / My courses.
8. Verify blocks on the left and right regions.
9. Verify the EVAGU library/category menu.
10. Verify course/category overview images.
11. Enable developer debugging temporarily and confirm there are no new
    exceptions from `theme/evagu`.

The changes deliberately avoid modifying Moodle core files.
