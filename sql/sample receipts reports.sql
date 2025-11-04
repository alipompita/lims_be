show tables;

select * from sample_receipts;

select s.code as study, r.basefol, r.specno, r.rejected from sample_receipts r
left join studies s on s.id = r.study_id;

-- sample reject stats by study and basefol 
select
	s.code as study,
    r.basefol,
    count(*) as samples_collected,
    sum(case when r.rejected = true then 1 else 0 end) as samples_rejected,
    sum(case when r.rejected = false or r.rejected is null then 1 else 0 end) as samples_accepted
from sample_receipts r
left join studies s on s.id = r.study_id
group by s.code, r.basefol
order by s.code, r.basefol;

-- sample reject stats by spectype and basefol 
select
	st.id as spectype_code,
    st.label as spectype,
    count(*) as samples_collected,
    sum(case when r.rejected = true then 1 else 0 end) as samples_rejected,
    sum(case when r.rejected = false or r.rejected is null then 1 else 0 end) as samples_accepted
from sample_receipts r
left join specimen_types st on st.id = r.spectype
group by st.id, st.label
order by st.id, st.label;

select * from studies;