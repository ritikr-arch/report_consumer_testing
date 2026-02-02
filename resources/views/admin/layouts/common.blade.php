<script>
$(document).ready(function() {
    // DELETE CATEGORY

    $('table').on('click', '.deleteCategory', function() {
        let id = $(this).data('id');
        let url = `{{ route('admin.category.delete', ':id') }}`;
        url = url.replace(':id', id);
        Swal.fire({
            title: 'Do you want to DELETE ?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#24695c',
            cancelButtonColor: '#d22d3d',
            confirmButtonText: 'Yes, Delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "GET",
                    url: url,
                    success: function(response) {
                        toastr.success('Category Deleted successfully')
                        setTimeout(() => {
                            location.reload()
                        }, 1000);
                    }
                });
            }
        });
    });

  $('table').on('click', '.deleteComplaintCategory', function() {
        let id = $(this).data('id');
        let url = `{{ route('admin.complaint.category.delete', ':id') }}`;
        url = url.replace(':id', id);
        Swal.fire({
            title: 'Do you want to DELETE ?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#24695c',
            cancelButtonColor: '#d22d3d',
            confirmButtonText: 'Yes, Delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "GET",
                    url: url,
                    success: function(response) {
                        toastr.success('Category Deleted successfully')
                        setTimeout(() => {
                            location.reload()
                        }, 1000);
                    }
                });
            }
        });
    });

    $('table').on('click', '.deleteEnquiryCategory', function() {
          let id = $(this).data('id');
          let url = `{{ route('admin.enqiry.category.delete', ':id') }}`;
          url = url.replace(':id', id);
          Swal.fire({
              title: 'Do you want to DELETE ?',
              icon: 'question',
              showCancelButton: true,
              confirmButtonColor: '#24695c',
              cancelButtonColor: '#d22d3d',
              confirmButtonText: 'Yes, Delete it!'
          }).then((result) => {
              if (result.isConfirmed) {
                  $.ajax({
                      type: "GET",
                      url: url,
                      success: function(response) {
                          toastr.success('Category Deleted successfully')
                          setTimeout(() => {
                              location.reload()
                          }, 1000);
                      }
                  });
              }
          });
      });

     $('table').on('click', '.deleteTipsAndAdvice', function() {
        let id = $(this).data('id');
        let url = `{{ route('admin.tips_advice.delete', ':id') }}`;
        url = url.replace(':id', id);
        Swal.fire({
            title: 'Do you want to DELETE ?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#24695c',
            cancelButtonColor: '#d22d3d',
            confirmButtonText: 'Yes, Delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "GET",
                    url: url,
                    success: function(response) {
                        toastr.success('Tips And Advice Deleted successfully')
                        setTimeout(() => {
                            location.reload()
                        }, 1000);
                    }
                });
            }
        });
    });
 $('table').on('click', '.deleteNotices', function() {
        let id = $(this).data('id');
        let url = `{{ route('admin.notices.delete', ':id') }}`;
        url = url.replace(':id', id);
        Swal.fire({
            title: 'Do you want to DELETE ?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#24695c',
            cancelButtonColor: '#d22d3d',
            confirmButtonText: 'Yes, Delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "GET",
                    url: url,
                    success: function(response) {
                        toastr.success('Notices Deleted successfully')
                        setTimeout(() => {
                            location.reload()
                        }, 1000);
                    }
                });
            }
        });
    });

    
    // DELETE Broachers & Presentation
    $('table').on('click', '.deleteBroacher', function() {
        let id = $(this).data('id');
        let url = `{{ route('admin.broachers.presentation.delete', ':id') }}`;
        url = url.replace(':id', id);
        Swal.fire({
            title: 'Do you want to DELETE ?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#24695c',
            cancelButtonColor: '#d22d3d',
            confirmButtonText: 'Yes, Delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "GET",
                    url: url,
                    success: function(response) {
                        toastr.success(
                            'Brochure And Presentation Deleted successfully')
                        setTimeout(() => {
                            location.reload()
                        }, 1000);
                    }
                });
            }
        });
    });

    // DELETE FAQ TYPE
    $('table').on('click', '.deleteFAQType', function() {
        let id = $(this).data('id');
        let url = `{{ route('admin.faq.type.delete', ':id') }}`;
        url = url.replace(':id', id);
        Swal.fire({
            title: 'Do you want to DELETE ?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#24695c',
            cancelButtonColor: '#d22d3d',
            confirmButtonText: 'Yes, Delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "GET",
                    url: url,
                    success: function(response) {
                        toastr.success('FAQ Type Deleted successfully')
                        setTimeout(() => {
                            location.reload()
                        }, 1000);
                    }
                });
            }
        });
    });


    // DELETE Announcement
    $('table').on('click', '.deleteConsumerCorner', function() {
        let id = $(this).data('id');
        let url = `{{ route('admin.consumer_corner.delete', ':id') }}`;
        url = url.replace(':id', id);
        Swal.fire({
            title: 'Do you want to DELETE ?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#24695c',
            cancelButtonColor: '#d22d3d',
            confirmButtonText: 'Yes, Delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "GET",
                    url: url,
                    success: function(response) {
                        toastr.success('Consumer Corner Deleted successfully')
                        setTimeout(() => {
                            location.reload()
                        }, 1000);
                    }
                });
            }
        });
    });

    // BANNER DELETE
    $('table').on('click', '.deleteBanner', function() {
        let id = $(this).data('id');
        let url = `{{ route('admin.banner.delete', ':id') }}`;
        url = url.replace(':id', id);
        Swal.fire({
            title: 'Do you want to DELETE ?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#24695c',
            cancelButtonColor: '#d22d3d',
            confirmButtonText: 'Yes, Delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "GET",
                    url: url,
                    success: function(response) {
                        toastr.success('Banner Deleted successfully')
                        setTimeout(() => {
                            location.reload()
                        }, 1000);
                    }
                });
            }
        });
    });

    // BRAND DELETE
    $('table').on('click', '.deleteBrand', function() {
        let id = $(this).data('id');
        let url = `{{ route('admin.brand.delete', ':id') }}`;
        url = url.replace(':id', id);
        Swal.fire({
            title: 'Do you want to DELETE ?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#24695c',
            cancelButtonColor: '#d22d3d',
            confirmButtonText: 'Yes, Delete it!'

        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "GET",
                    url: url,
                    success: function(response) {
                        toastr.success('Brand Deleted successfully')
                        setTimeout(() => {
                            location.reload()
                        }, 1000);
                    }
                });
            }
        });

    });


    // DELETE UOM
    $('table').on('click', '.deleteuom', function () {
    let id = $(this).data('id');
    let url = `{{ route('admin.uom.delete', ':id') }}`;
    url = url.replace(':id', id);

    Swal.fire({
        title: 'Do you want to DELETE ?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#24695c',
        cancelButtonColor: '#d22d3d',
        confirmButtonText: 'Yes, Delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: "GET",
                url: url,
                success: function (response) {
                    toastr.success('UOM Deleted successfully');

                    // Check if current page is now empty
                    let remainingRows = $('table tbody tr').length;
                    let currentPage = parseInt(new URLSearchParams(window.location.search).get('page')) || 1;

                    if (remainingRows === 1 && currentPage > 1) {
                        // Go to previous page if last item deleted
                        let newPage = currentPage - 1;
                        window.location.href = updateQueryStringParameter(window.location.href, 'page', newPage);
                    } else {
                        // Reload current page
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    }
                }
            });
        }
    });
});

// Helper function to update query string
function updateQueryStringParameter(uri, key, value) {
    let re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
    let separator = uri.indexOf('?') !== -1 ? "&" : "?";
    if (uri.match(re)) {
        return uri.replace(re, '$1' + key + "=" + value + '$2');
    } else {
        return uri + separator + key + "=" + value;
    }
}




    // DELETE MARKET

    $('table').on('click', '.deleteMarket', function() {

        let id = $(this).data('id');

        let url = `{{ route('admin.market.delete', ':id') }}`;

        url = url.replace(':id', id);

        Swal.fire({

            title: 'Do you want to DELETE ?',

            icon: 'question',

            showCancelButton: true,

            confirmButtonColor: '#24695c',

            cancelButtonColor: '#d22d3d',

            confirmButtonText: 'Yes, Delete it!'

        }).then((result) => {

            if (result.isConfirmed) {

                $.ajax({

                    type: "GET",
                    url: url,
                    success: function(response) {
                        toastr.success('Market Deleted successfully')
                        setTimeout(() => {
                            location.reload()
                        }, 1000);
                    }
                });
            }
        });



    });



    //DELETE COMMODITY

    $('table').on('click', '.deleteCommodity', function() {

        let id = $(this).data('id');

        let url = `{{ route('admin.commodity.delete', ':id') }}`;

        url = url.replace(':id', id);

        if (id) {

            Swal.fire({

                title: 'Do you want to DELETE ?',

                icon: 'question',

                showCancelButton: true,

                confirmButtonColor: '#24695c',

                cancelButtonColor: '#d22d3d',

                confirmButtonText: 'Yes, Delete it!'

            }).then((result) => {

                if (result.isConfirmed) {

                    $.ajax({

                        type: "GET",

                        url: url,

                        success: function(response) {

                            toastr.success('Commodity Deleted successfully')

                            setTimeout(() => {

                                location.reload()

                            }, 1000);

                        }

                    });

                }

            });

        }

    });



    //DELETE ZONE

    $('table').on('click', '.deleteZone', function() {

        let id = $(this).data('id');

        let url = `{{ route('admin.zone.delete', ':id') }}`;

        url = url.replace(':id', id);

        if (id) {

            Swal.fire({

                title: 'Do you want to DELETE ?',

                icon: 'question',

                showCancelButton: true,

                confirmButtonColor: '#24695c',

                cancelButtonColor: '#d22d3d',

                confirmButtonText: 'Yes, Delete it!'

            }).then((result) => {

                if (result.isConfirmed) {

                    $.ajax({

                        type: "GET",

                        url: url,

                        success: function(response) {

                            toastr.success('Zone Deleted successfully')

                            setTimeout(() => {

                                location.reload()

                            }, 1000);

                        }

                    });

                }

            });

        }

    });



    //DELETE USER

    $('table').on('click', '.deleteUser', function() {

        let id = $(this).data('id');
        let url = `{{ route('admin.user.delete', ':id') }}`;
        url = url.replace(':id', id);
        if (id) {
            Swal.fire({
                title: 'Do you want to DELETE ?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#24695c',
                cancelButtonColor: '#d22d3d',
                confirmButtonText: 'Yes, Delete it!'
            }).then((result) => {

                if (result.isConfirmed) {
                    $.ajax({
                        type: "GET",
                        url: url,
                        success: function(response) {

                            console.log(response.success);

                            if (!response.success) {
                                toastr.error(response.message)
                            } else {
                                toastr.success('User Deleted successfully')
                                setTimeout(() => {
                                    location.reload()
                                }, 1000);
                            }
                        }
                    });
                }
            });
        }

    });

    //DELETE Quck Link

    $('table').on('click', '.deleteQucklink', function() {
        let id = $(this).data('id');
        let url = `{{ route('admin.quick.delete', ':id') }}`;
        url = url.replace(':id', id);
        if (id) {
            Swal.fire({
                title: 'Do you want to DELETE ?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#24695c',
                cancelButtonColor: '#d22d3d',
                confirmButtonText: 'Yes, Delete it!'
            }).then((result) => {

                if (result.isConfirmed) {
                    $.ajax({
                        type: "GET",
                        url: url,
                        success: function(response) {
                            // console.log(response);
                            if (!response.success) {
                                toastr.error(response.message)
                            } else {
                                toastr.success('Quick Links Deleted successfully')
                                setTimeout(() => {
                                    location.reload()
                                }, 1000);
                            }
                        }
                    });
                }
            });
        }

    });



    //DELETE POST

    $('table').on('click', '.deletePost', function() {

        let id = $(this).data('id');
        let url = `{{ route('admin.news.delete', ':id') }}`;
        url = url.replace(':id', id);
        if (id) {
            Swal.fire({
                title: 'Do you want to DELETE ?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#24695c',
                cancelButtonColor: '#d22d3d',
                confirmButtonText: 'Yes, Delete it!'
            }).then((result) => {

                if (result.isConfirmed) {
                    $.ajax({
                        type: "GET",
                        url: url,
                        success: function(response) {
                            // console.log(response);
                            if (!response.success) {
                                toastr.error(response.message)
                            } else {
                                toastr.success('Post Deleted successfully')
                                setTimeout(() => {
                                    location.reload()
                                }, 1000);
                            }
                        }
                    });
                }
            });
        }

    });



    //DELETE USER

    $('table').on('click', '.deleteRole', function() {

        let id = $(this).data('id');
        let url = `{{ route('admin.role.delete', ':id') }}`;
        url = url.replace(':id', id);
        if (id) {
            Swal.fire({
                title: 'Do you want to DELETE ?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#24695c',
                cancelButtonColor: '#d22d3d',
                confirmButtonText: 'Yes, Delete it!'
            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({

                        type: "GET",

                        url: url,

                        success: function(response) {

                            if (!response.success) {

                                toastr.error(response.message)

                            } else {

                                toastr.success('Role Deleted successfully')

                                setTimeout(() => {

                                    location.reload()

                                }, 1000);

                            }

                        }

                    });

                }

            });

        }

    });



    // DELETE SURVEY AND ITS RELATED DATA.

    $('table').on('click', '.deleteSurvey', function() {

        let id = $(this).data('id');

        let url = `{{ route('admin.survey.delete', ':id') }}`;

        url = url.replace(':id', id);

        if (id) {

            Swal.fire({
                title: 'If you delete this survey, all related data will also be deleted. Do you want to proceed?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#24695c',
                cancelButtonColor: '#d22d3d',
                confirmButtonText: 'Yes, Delete it!'
            }).then((result) => {


                if (result.isConfirmed) {

                    $.ajax({
                        type: "GET",
                        url: url,
                        success: function(response) {

                            console.log(response);
                            if (!response.success) {
                                toastr.error(response.message)
                            } else {
                                toastr.success(response.message)

                                setTimeout(() => {
                                    location.reload()
                                }, 1000);
                            }
                        }
                    });
                }
            });
        }
    });

    // APPROVE THE SURVEY
    $('table').on('click', '.statuss', function() {
        let id = $(this).data('id');
        let url = `{{ route('admin.submitted.survey.update.status', ':id') }}`;
        url = url.replace(':id', id);
        if (id) {
            Swal.fire({
                title: 'Do you want to update the status?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#24695c',
                cancelButtonColor: '#d22d3d',
                confirmButtonText: 'Yes, Update it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "GET",
                        url: url,
                        success: function(response) {
                            // console.log(response)
                            if (!response.success) {
                                toastr.error(response.message)
                            } else {
                                toastr.success('Status updated successfully')
                                setTimeout(() => {
                                    location.reload()
                                }, 1000);
                            }
                        }
                    });
                }
            });
        }

    });

    // APPROVE THE SURVEY
    $('table').on('click', '.approveSurvey', function() {
        let id = $(this).data('id');
        let url = `{{ route('admin.approve.survey', ':id') }}`;
        url = url.replace(':id', id);
        if (id) {
            Swal.fire({
                title: 'Do you want to approve?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#24695c',
                cancelButtonColor: '#d22d3d',
                confirmButtonText: 'Yes, approve it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "GET",
                        url: url,
                        success: function(response) {
                            if (!response.success) {
                                toastr.error(response.message)
                            } else {
                                toastr.success('Survey approved successfully')
                                setTimeout(() => {
                                    location.reload()
                                }, 1000);
                            }
                        }
                    });
                }
            });
        }
    });

    // PUBLISH THE SURVEY
    $('table').on('click', '.publish', function() {
        let id = $(this).data('id');
        let url = `{{ route('admin.approve.submitted.survey', ':id') }}`;
        url = url.replace(':id', id);
        if (id) {
            Swal.fire({
                title: 'Do you want to publish?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#24695c',
                cancelButtonColor: '#d22d3d',
                confirmButtonText: 'Yes, Publish it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "GET",
                        url: url,
                        success: function(response) {
                            // console.log(response)
                            if (!response.success) {
                                toastr.error(response.message)
                            } else {
                                toastr.success('Survey published successfully')
                                setTimeout(() => {
                                    location.reload()
                                }, 1000);
                            }
                        }
                    });
                }
            });
        }
    });

    $('table').on('click', '.submittedSurveyStatus', function() {
        let id = $(this).data('id');
        let url = `{{ route('admin.submitted.survey.approve', ':id') }}`;
        url = url.replace(':id', id);
        if (id) {
            Swal.fire({
                title: 'Do you want to approve the survey?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#24695c',
                cancelButtonColor: '#d22d3d',
                confirmButtonText: 'Yes, Update it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "GET",
                        url: url,
                        success: function(response) {
                            // console.log(response)
                            if (!response.success) {
                                toastr.error(response.message)
                            } else {
                                toastr.success('Status updated successfully')
                                setTimeout(() => {
                                    location.reload()
                                }, 1000);
                            }
                        }
                    });
                }
            });
        }

    });

    // DELET FAQ
    $('table').on('click', '.deleteFaq', function() {

        let id = $(this).data('id');
        let url = `{{ route('admin.faq.delete', ':id') }}`;
        url = url.replace(':id', id);
        if (id) {
            Swal.fire({
                title: 'Do you want to DELETE ?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#24695c',
                cancelButtonColor: '#d22d3d',
                confirmButtonText: 'Yes, Delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "GET",
                        url: url,
                        success: function(response) {
                            if (!response.success) {
                                toastr.error(response.message)
                            } else {
                                toastr.success(response.message)
                                setTimeout(() => {
                                    location.reload()
                                }, 1000);
                            }
                        }
                    });
                }
            });

        }

    });

    // DELETE IMAGE GALLERY
    $('table').on('click', '.deleteImageGallery', function() {

        let id = $(this).data('id');
        let url = `{{ route('admin.image.gallery.delete', ':id') }}`;
        url = url.replace(':id', id);
        if (id) {
            Swal.fire({
                title: 'Do you want to DELETE ?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#24695c',
                cancelButtonColor: '#d22d3d',
                confirmButtonText: 'Yes, Delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "GET",
                        url: url,
                        success: function(response) {
                            if (!response.success) {
                                toastr.error(response.message)
                            } else {
                                toastr.success(response.message)
                                setTimeout(() => {
                                    location.reload()
                                }, 1000);
                            }
                        }
                    });
                }
            });

        }

    });

    // UPDATE MULTIPLE SUBMITTED SURVEYS
    $('#selectButton').on('click', function() {
        let selectedValues = [];

        $('input[name="selected[]"]:checked').each(function() {
            selectedValues.push($(this).val());
        });

        if(selectedValues.length>0){
            Swal.fire({
                title: 'Do you want to update the status ?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#24695c',
                cancelButtonColor: '#d22d3d',
                confirmButtonText: 'Yes, Update it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{route("admin.approve.survey.report")}}',
                        type: 'get',
                        data:{ids:selectedValues},
                        success: function(response){
                            if (!response.success) {
                                toastr.error(response.message)
                            } else {
                                toastr.success(response.message)
                                setTimeout(() => {
                                    location.reload()
                                }, 1000);
                            }
                        },
                        error: function(xhr) {
                            let errors = xhr.responseJSON.errors;
                        }
                    });
                }
            });
        }else{
            alert('Please select atleast one');
        }
    });

    // PUBLISH MULTIPLE SURVEYS
    $('#publishButton').on('click', function() {
        let selectedValues = [];

        $('input[name="selected[]"]:checked').each(function() {
            selectedValues.push($(this).val());
        });

        if(selectedValues.length>0){
            Swal.fire({
                title: 'Do you want to publish the survey?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#24695c',
                cancelButtonColor: '#d22d3d',
                confirmButtonText: 'Yes, publish it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{route("admin.publish.survey")}}',
                        type: 'get',
                        data:{ids:selectedValues},
                        success: function(response){
                            if (!response.success) {
                                toastr.error(response.message)
                            } else {
                                toastr.success(response.message)
                                setTimeout(() => {
                                    location.reload()
                                }, 1000);
                            }
                        },
                        error: function(xhr) {
                            let errors = xhr.responseJSON.errors;
                        }
                    });
                }
            });
        }else{
            alert('Please select atleast one');
        }
    });

    $("#start_date").datepicker({
        dateFormat: "dd-mm-yy",
        onSelect: function (selectedDate) {
        $("#end_date").prop("disabled", false);

            if ($("#end_date").hasClass("hasDatepicker")) {
                $("#end_date").datepicker("destroy");
            }

            $("#end_date").datepicker({
               dateFormat: "dd-mm-yy",
               minDate: selectedDate
            });
        }
    });
});

</script>
<!-- Select2 CSS & JS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function () {
    const defaultCode = "+1-869"; // Default selected
    const $select = $(".countryCodeSelect");

    fetch("https://restcountries.com/v3.1/all?fields=name,cca2,idd")
      .then(response => response.json())
      .then(data => {
        // Sort countries alphabetically by name
        data.sort((a, b) => a.name.common.localeCompare(b.name.common));

        data.forEach(country => {
          const name = country.name.common;
          const root = country.idd?.root || "";
          const suffix = country.idd?.suffixes?.[0] || "";
          const code = root + suffix;

          if (code) {
            const formattedCode = root === "+1" && suffix ? `${root}-${suffix}` : code;
            const flagUrl = `https://flagcdn.com/w20/${country.cca2.toLowerCase()}.png`;

            const option = new Option(`${formattedCode} ${name}`, formattedCode, false, false);
            $(option).attr('data-flag', flagUrl);
            $(option).attr('data-name', name);
            $(option).attr('data-code', formattedCode);
            $select.append(option);
          }
        });

        // Initialize Select2
        $select.select2({
          templateResult: formatCountry,
          templateSelection: formatCountry,
          escapeMarkup: markup => markup
        });

        // Set default value
        $select.val(defaultCode).trigger('change');
      });

    function formatCountry(state) {
      if (!state.id) {
        return state.text;
      }
      const flagUrl = $(state.element).data('flag');
      const name = $(state.element).data('name');
      const code = $(state.element).data('code');

      if (flagUrl && name && code) {
        return `<img src="${flagUrl}" style="width: 20px; vertical-align: middle; margin-right: 6px;" /> ${code} ${name}`;
      }
      return state.text;
    }
  });
</script>