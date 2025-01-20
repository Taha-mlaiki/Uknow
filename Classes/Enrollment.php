<?php
 namespace Classes;

 class Enrollment {
        public function __construct(
            private int $studentId,
            private int $courseId,
            private string $enrollmentDate
        ) {}

        public function getStudentId(): int
        {
            return $this->studentId;
        }
    
        public function getCourseId(): int
        {
            return $this->courseId;
        }
    
        public function getEnrollmentDate(): string
        {
            return $this->enrollmentDate;
        }
    
        public function setStudentId(int $studentId): void
        {
            $this->studentId = $studentId;
        }
    
        public function setCourseId(int $courseId): void
        {
            $this->courseId = $courseId;
        }
    
        public function setEnrollmentDate(string $enrollmentDate): void
        {
            $this->enrollmentDate = $enrollmentDate;
        }
 }